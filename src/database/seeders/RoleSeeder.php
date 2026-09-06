<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role_admin             =   Role::create(['name'  =>  'admin']);
        $role_empleado_maestro   =   Role::create(['name'  =>  'empleado maestro']);
        $role_empleado          =   Role::create(['name'  =>  'empleado']);
    }
}
