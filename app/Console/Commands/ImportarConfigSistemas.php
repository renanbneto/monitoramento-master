<?php

namespace App\Console\Commands;

use App\Http\Controllers\jwt\JWT;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImportarConfigSistemas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'importar:config:sistemas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importa a config de sistemas atualizada do Sia Auth para conhecer todos os sistemas e configurações destes';

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


        $time = time();
        try {

            // Move para bkp com timestamp
            if ( Storage::disk('config')->exists('sistemas.php') ) {
                Storage::disk('config')->move('sistemas.php','sistemas.php.'.$time);
            }
            
             $chave = env('SIA_CHAVE_ASSINATURA');
             $id_sw = env('SIA_ID_SOFTWARE');
             $nome_sw = env('SIA_ID_SOFTWARE');

             $token = JWT::encode([
                    'id' => $id_sw,
                    'nome' => $nome_sw
                ], $chave);

            $retorno = Http::withOptions(['verify' => env('SIA_SSL_VERIFY')])->retry(3)->timeout(3)->get(env('DOMINIO_SIA').':'.env('PORTA_SIA').'/api/update/sistemas',[
                'token' => $token,
                'sw' => $nome_sw
            ]);

            Storage::disk('config')->put('sistemas.php',$retorno); 

            Storage::disk('config')->delete('sistemas.php.'.$time);

        } catch (\Throwable $th) {
            echo "DEU RUIM ".$th->getMessage()."\n";
            Log::error("Erro ao atualizar a config sistemas com o SIA AUTH",[]);
            
            if ( Storage::disk('config')->exists('sistemas.php.'.$time) && !Storage::disk('config')->exists('sistemas.php') ) {
                
                try {

                    Storage::disk('config')->move('sistemas.php.'.$time, 'sistemas.php');
                    Storage::disk('config')->delete('sistemas.php.'.$time);
                } catch (\Throwable $th) {
                    //throw $th;
                }
            }
            
        }

        return 0;
    }
}
