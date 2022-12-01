<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    use HasRoles;
    
    public function run()
    {
        $user =new User();
        $user->name="admin";
        $user->email="admin@fayweb.mx";
        $user->areas_id="1";
        $user->password= Hash::make("password");
        $user->save();
    }
}
