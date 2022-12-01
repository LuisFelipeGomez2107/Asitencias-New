<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role1 =Role::create(['name' => 'Admin']);
        $role2 =Role::create(['name' => 'Instalador']);
        $role3 =Role::create(['name' => 'Supervisor']);
    }
}
