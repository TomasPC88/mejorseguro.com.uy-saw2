<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();
        $this->call(AdminTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(ConfiguracionesTableSeeder::class);
        DB::commit();
    }
}
