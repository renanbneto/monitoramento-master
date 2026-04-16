<?php

namespace App\Console\Commands;

use App\Log\Log;
use App\Models\AuditoriaMigration;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class EnviarLogParaAuditoria extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'enviar:logs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enviar Logs para o Sistema de Auditoria';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {

            $logDirectory = storage_path('logs');
            $filesInLogDirectory = File::files($logDirectory);
            
            $yesterday = Carbon::now(-3)->subDay();

            foreach ($filesInLogDirectory as $file) {
                $fileName = $file->getFilename();

                $fileNameLength = strlen($fileName);

                $date = Carbon::parse(substr($fileName, ($fileNameLength-14), 10), -3);

                // Verifica se o nome do arquivo contém a data de ontem
                if ($date->lte($yesterday)) {
                    $migrationExists = AuditoriaMigration::where('migration', $fileName)->exists();

                    if (!$migrationExists) {
                        $logPath = $logDirectory . '/' . $fileName;

                        $response = Http::withOptions([
                            //'debug' => true  // Ativa a depuração
                        ])
                            ->attach('logFile', file_get_contents($logPath), $fileName)
                            ->post(config('sistemas.Auditoria.appUrl') . "/api/uploadLog"); //uploadLog_Auditoria

                        if ($response->status() == 200) {
                            // Salva o arquivo na tabela de migração
                            $auditoriaMigration = new AuditoriaMigration();
                            $auditoriaMigration->migration = $fileName;
                            $auditoriaMigration->save();                            
                            echo "Arquivo enviado com sucesso!\n";
                        }else {
                            echo $response->status();
                        }
                    }
                }
            }
        } catch (\Throwable $th) {
            echo $th->getMessage();
            echo "Erro ao enviar arquivo.\n";
        }
    }
}
