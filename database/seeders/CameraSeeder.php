<?php

namespace Database\Seeders;

use App\Models\Camera;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CameraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/data/cameras.json");
        $cameras = json_decode($json);

        foreach ($cameras as $camera) {
            $ativo = $camera->ativo == '1' ? true : false;
            Camera::updateOrCreate(
                [
                    'servidor' => $camera->servidor,
                    'cidade' => $camera->cidade,
                    'camera' => $camera->camera
                ],
                [
                    'ip' => $camera->ip,
                    'porta' => $camera->porta,
                    'local_nome' => $camera->local_nome,
                    'lat' => $camera->lat,
                    'lng' => $camera->lng,
                    'usuario' => $camera->usuario,
                    'senha' => $camera->senha,
                    'protocolo' => $camera->protocolo,
                    'vms' => $camera->vms,
                    'formato' => $camera->formato,
                    'hostname' => $camera->hostname,
                    'link' => $camera->link,
                    'ativo' => $ativo
                ]
            );
        }
    }
}
