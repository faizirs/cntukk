<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('spp')->insert([
            ['id_spp' => 1, 'tahun' => 2023, 'nominal' => 1000000],
            ['id_spp' => 2, 'tahun' => 2023, 'nominal' => 1200000],
            ['id_spp' => 3, 'tahun' => 2023, 'nominal' => 1500000],
        ]);
    }
}
