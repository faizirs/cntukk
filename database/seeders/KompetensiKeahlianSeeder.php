<?php

namespace Database\Seeders;

use Illuminate\Container\Attributes\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\KompetensiKeahlian;

class KompetensiKeahlianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        KompetensiKeahlian::create([
            'id_kompetensi_keahlian' => 1,
            'nama_kompetensi_keahlian' => 'Rekayasa Perangkat Lunak'
        ]);
        KompetensiKeahlian::create([
            'id_kompetensi_keahlian' => 2,
            'nama_kompetensi_keahlian' => 'Teknik Komputer dan Jaringan'
        ]);
        KompetensiKeahlian::create([
            'id_kompetensi_keahlian' => 3,
            'nama_kompetensi_keahlian' => 'Multimedia'
        ]);
    }
}
