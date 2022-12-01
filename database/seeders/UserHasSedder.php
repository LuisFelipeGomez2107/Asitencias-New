<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\users_has_status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserHasSedder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $usuarios = User::all();
        foreach ($usuarios as $user) {
            users_has_status::create([
                'user_id' => $user->id,
                'status' => 1,
            ]);
        }
    }
}
