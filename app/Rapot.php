<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rapot extends Model
{
    protected $table = 'rapot';

    protected $fillable = [
        'siswa_id', 'kelas_id', 'guru_id', 'mapel_id', 
        'p_nilai', 'p_predikat', 'p_deskripsi', 
        'k_nilai', 'k_predikat', 'k_deskripsi'
    ];

    // Relasi ke Mapel
    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'mapel_id');
    }

    // Relasi ke Siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    // Relasi ke Kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    // Relasi ke Guru (Jika perlu)
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }
}
