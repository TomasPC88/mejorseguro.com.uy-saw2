<?php

use App\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->insert([
            [
                'id'                => 3,
                'name'              => 'Soporte Sitios',
                'email'             => 'soporte@sitios.com.uy',
                'password'          => Hash::make('soportesitios!'),
                'remember_token'    => NULL,
                'created_at'        => '2017-07-06 23:36:15',
                'updated_at'        => '2017-07-06 23:36:15'
            ]
        ]);
    }
}
