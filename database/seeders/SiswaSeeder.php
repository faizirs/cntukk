<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('siswa')->insert([
            'nisn' => '1234567890',
            'id_user' => 2,
            'nis' => '12345678',
            'nama' => 'John Doe',
            'id_kelas' => 1,
            'alamat' => 'Jl. Contoh No. 123',
            'no_telp' => '081234567890',
            'id_spp' => 1,
        ]);
    }
}
