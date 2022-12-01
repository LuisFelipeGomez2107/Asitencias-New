<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CreateUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $UserAdmin =User::create([
            'name' => 'Admin',
            'email' => 'admin@fayweb.mx',
            'password' => Hash::make('12345678'),
            'areas_id' => '0',
        ])->assignRole('Admin');
        $UserSupervisor =User::create([
            'name' => 'Instalador',
            'email' => 'instalador@fayweb.mx',
            'password' => Hash::make('12345678'),
            'areas_id' => '0',
        ])->assignRole('Instalador');
    }
}
