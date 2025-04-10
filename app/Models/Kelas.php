<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;
    protected $table = 'kelas';
    protected $primaryKey = 'id_kelas';
    protected $fillable = ['nama_kelas', 'kompetensi_keahlian_id'];

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'id_kelas', 'id_kelas');
    }
    public function kompetensiKeahlian(){
        return $this->belongsTo(KompetensiKeahlian::class, 'kompetensi_keahlian_id', 'id_kompetensi_keahlian');
    }
}
