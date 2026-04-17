<?php

namespace App\Console\Commands;

use App\Models\Camera;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportUrbsCamerasCommand extends Command
{
    protected $signature = 'cameras:import
        {--path= : Caminho para o .xlsx (padrão: docs/Json URBS.xlsx na raiz do projeto)}
        {--sheet= : Nome exato da aba (padrão: Importação monitoramento)}
        {--json= : Alternativa: importar de um arquivo JSON no formato do CameraSeeder (array de objetos)}';

    protected $description = 'Importa câmeras da aba URBS (planilha) ou de JSON; reescrita de link em leitura via Camera (CAMERAS_LINK_URL_* no .env)';

    private const DEFAULT_XLSX = 'docs/Json URBS.xlsx';

    private const DEFAULT_SHEET = 'Importação monitoramento';

    private const COLUMNS = [
        'A' => 'servidor',
        'B' => 'cidade',
        'C' => 'ip',
        'D' => 'porta',
        'E' => 'camera',
        'F' => 'local_nome',
        'G' => 'lat',
        'H' => 'lng',
        'I' => 'usuario',
        'J' => 'senha',
        'K' => 'protocolo',
        'L' => 'vms',
        'M' => 'formato',
        'N' => 'hostname',
        'O' => 'link',
        'P' => 'ativo',
    ];

    public function handle(): int
    {
        $jsonPath = $this->option('json');
        if ($jsonPath) {
            return $this->importFromJson($jsonPath);
        }

        return $this->importFromXlsx();
    }

    private function importFromJson(string $path): int
    {
        $full = $this->absolutePath($path);
        if (! File::exists($full)) {
            $this->error("Arquivo não encontrado: {$full}");

            return 1;
        }
        $cameras = json_decode(File::get($full), true);
        if (! is_array($cameras)) {
            $this->error('JSON inválido: esperado array no root.');

            return 1;
        }
        $n = $this->persistCameras($cameras);
        $this->info("Importação JSON: {$n} registro(s) processados.");

        return 0;
    }

    private function importFromXlsx(): int
    {
        if (! class_exists(\ZipArchive::class)) {
            $this->error('A extensão PHP "zip" é necessária para ler .xlsx. Habilite extension=zip no php.ini ou use --json=caminho/arquivo.json');

            return 1;
        }

        $rel = $this->option('path') ?: self::DEFAULT_XLSX;
        $full = $this->absolutePath($rel);
        if (! File::exists($full)) {
            $this->error("Planilha não encontrada: {$full}");

            return 1;
        }

        $sheetName = $this->option('sheet') ?: self::DEFAULT_SHEET;

        try {
            $spreadsheet = IOFactory::load($full);
        } catch (\Throwable $e) {
            $this->error('Falha ao abrir planilha: '.$e->getMessage());

            return 1;
        }

        $sheet = $spreadsheet->getSheetByName($sheetName);
        if ($sheet === null) {
            $this->error("Aba \"{$sheetName}\" não encontrada. Abas: ".implode(', ', $spreadsheet->getSheetNames()));

            return 1;
        }

        $highestRow = (int) $sheet->getHighestRow();
        $rows = [];
        for ($r = 2; $r <= $highestRow; $r++) {
            $row = [];
            $empty = true;
            foreach (array_keys(self::COLUMNS) as $col) {
                $coord = $col.$r;
                $val = $sheet->getCell($coord)->getCalculatedValue();
                if ($val !== null && $val !== '') {
                    $empty = false;
                }
                $row[self::COLUMNS[$col]] = $val;
            }
            if ($empty) {
                continue;
            }
            $rows[] = $this->normalizeRow($row);
        }

        $n = $this->persistCameras($rows);
        $this->info("Importação XLSX: {$n} linha(s) com servidor+cidade+camera preenchidos.");

        return 0;
    }

    private function normalizeRow(array $row): array
    {
        foreach (['servidor', 'cidade', 'camera', 'local_nome', 'ip', 'hostname', 'link', 'usuario', 'senha', 'protocolo', 'vms', 'formato'] as $k) {
            if (isset($row[$k]) && $row[$k] !== null) {
                $row[$k] = is_string($row[$k]) ? trim($row[$k]) : $row[$k];
            }
        }
        if (isset($row['porta']) && $row['porta'] !== null && $row['porta'] !== '') {
            $row['porta'] = (string) $row['porta'];
        }
        $row['lat'] = $this->sanitizeLatitude($row['lat'] ?? null);
        $row['lng'] = $this->sanitizeLongitude($row['lng'] ?? null);
        $row['ativo'] = $this->normalizeAtivo($row['ativo'] ?? false);

        return $row;
    }

    private function sanitizeLatitude($value): ?string
    {
        return $this->sanitizeGeoNumber($value, -90.0, 90.0);
    }

    private function sanitizeLongitude($value): ?string
    {
        return $this->sanitizeGeoNumber($value, -180.0, 180.0);
    }

    /**
     * Remove #REF! e outros lixos de planilha; retorna string numérica ou null.
     */
    private function sanitizeGeoNumber($value, float $min, float $max): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }
        $s = trim((string) $value);
        if ($s === '' || preg_match('/^#/u', $s)) {
            return null;
        }
        $normalized = str_replace(',', '.', $s);
        if (! is_numeric($normalized)) {
            return null;
        }
        $f = (float) $normalized;
        if ($f < $min || $f > $max || ! is_finite($f)) {
            return null;
        }

        return (string) $f;
    }

    private function normalizeAtivo($value): bool
    {
        if ($value === null || $value === '') {
            return false;
        }
        if (is_bool($value)) {
            return $value;
        }
        $s = strtolower(trim((string) $value));

        return in_array($s, ['1', 'true', 'sim', 's', 'yes'], true);
    }

    /**
     * @param  array<int, array<string, mixed>>  $rows
     */
    private function persistCameras(array $rows): int
    {
        $count = 0;
        foreach ($rows as $data) {
            if (empty($data['servidor']) || empty($data['cidade']) || empty($data['camera'])) {
                continue;
            }
            $ativo = $this->normalizeAtivo($data['ativo'] ?? false);
            Camera::updateOrCreate(
                [
                    'servidor' => $data['servidor'],
                    'cidade' => $data['cidade'],
                    'camera' => $data['camera'],
                ],
                [
                    'ip' => $data['ip'] ?? null,
                    'porta' => isset($data['porta']) ? (string) $data['porta'] : null,
                    'local_nome' => $data['local_nome'] ?? null,
                    'lat' => $this->sanitizeLatitude($data['lat'] ?? null),
                    'lng' => $this->sanitizeLongitude($data['lng'] ?? null),
                    'usuario' => $data['usuario'] ?? null,
                    'senha' => $data['senha'] ?? null,
                    'protocolo' => $data['protocolo'] ?? null,
                    'vms' => $data['vms'] ?? null,
                    'formato' => $data['formato'] ?? null,
                    'hostname' => $data['hostname'] ?? null,
                    'link' => $data['link'] ?? null,
                    'ativo' => $ativo,
                ]
            );
            $count++;
        }

        return $count;
    }

    private function absolutePath(string $path): string
    {
        if ($path !== '' && ($path[0] === '/' || $path[0] === '\\')) {
            return $path;
        }
        if (preg_match('#^[A-Za-z]:[\\\\/]#', $path)) {
            return $path;
        }

        return base_path($path);
    }
}
