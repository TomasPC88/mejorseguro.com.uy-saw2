<?php

use App\Models\Configuracion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ConfiguracionesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Schema::hasTable('configuraciones')){
            Configuracion::firstOrCreate(['id' => 1],[
                'app_name' => 'SAW2'
            ]);
        }
    }
}
