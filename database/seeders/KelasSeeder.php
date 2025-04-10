<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kelas')->insert([
            ['id_kelas' => 1, 'nama_kelas' => '10 RPL', 'kompetensi_keahlian_id' => 1],
            ['id' => 2, 'nama_kelas' => '11 RPL', 'kompetensi_keahlian_id' => 1],
            ['id' => 3, 'nama_kelas' => '12 TKJ', 'kompetensi_keahlian_id' => 2],
        ]);
    }
}
