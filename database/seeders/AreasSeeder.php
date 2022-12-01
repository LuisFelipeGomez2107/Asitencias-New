<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AreasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('areas')->insert([
            'name' => 'WM',
          ]);
        DB::table('areas')->insert([
            'name' => 'PFMOT',
        ]);
        DB::table('areas')->insert([
            'name' => 'SBD',
        ]);
        DB::table('areas')->insert([
            'name' => 'Colgate/Alen',
        ]);
        DB::table('areas')->insert([
            'name' => 'SRC',
        ]);
        DB::table('areas')->insert([
            'name' => 'LOreal',
        ]);
        DB::table('areas')->insert([
            'name' => 'Pantaco',
        ]);
    }
}
