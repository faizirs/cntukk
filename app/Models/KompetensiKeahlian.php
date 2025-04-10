<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KompetensiKeahlian extends Model
{
    protected $table = 'kompetensi_keahlian';
    protected $primaryKey = 'id_kompetensi_keahlian';
    protected $fillable = ['nama_kompetensi_keahlian'];

    public function kelas(){
        return $this->hasMany(Kelas::class, 'id_kompetensi_keahlian', 'id_kompetensi_keahlian');
    }
}
