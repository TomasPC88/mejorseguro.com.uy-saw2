<?php

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
            $tables = collect([
                'roles',
                'permissions',
                'model_has_permissions',
                'model_has_roles',
                'role_has_permissions'
            ]);

            $tables->each(function ($t) {
                DB::table($t)->truncate();
            });
        Schema::enableForeignKeyConstraints();

        // Limpiar cache de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => "asignar-roles", 'guard_name' => 'admin']);
        //Crear permisos para seccion administradores y roles
        $sections = [
            'administradores',
            'portadas',
            'roles',
            'newsletters',
            'configuracion',
        ];
        foreach ($sections as $section) {
            $permisos = collect([]);
            foreach (['listar', 'nuevo', 'editar', 'crear', 'actualizar', 'borrar'] as $action) {
                $permisos->push("$action-$section");
                Permission::create(['name' => "$action-$section", 'guard_name' => 'admin']);
            }
            Role::create(['name' => "rol_$section", 'guard_name' => 'admin'])->givePermissionTo($permisos->toArray());
        }
        $role = Role::firstOrCreate(['guard_name' => 'admin', 'name' => 'super-admin']);

        Admin::get()->each(function ($admin) use ($role) {
            $admin->assignRole($role);
        });
    }
}
