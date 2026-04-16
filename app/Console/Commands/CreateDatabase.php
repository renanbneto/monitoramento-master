<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'criar:banco';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Criar banco de dados use criar:banco nome';

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
	
	$dbname = env('DB_DATABASE');
	$dbhost = env('DB_HOST');
	$dbuser = env('DB_USERNAME');
	$dbpass = env('DB_PASSWORD');
	try {
            $db = new \PDO("pgsql:host=$dbhost;dbname=postgres", $dbuser, $dbpass);
            $test = $db->exec("CREATE DATABASE \"$dbname\";");
            if($test === false)
                throw new \Exception($db->errorInfo()[2]);
            $this->info(sprintf('Successfully created %s database', $dbname));
	}
	catch (\Exception $exception) {
            $this->info(sprintf('Error on create %s database - %s', $dbname,$exception->getMessage()));
	}	

    }
}
