<?php

namespace Database\Seeders;

use App\Models\Tipo;
use Database\Seeders\Tipos;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CameraSeeder::class);

    }
}
