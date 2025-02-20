<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Siswa extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'no_induk', 'nis', 'nama_siswa', 'kelas_id', 'jk', 
        'telp', 'tmp_lahir', 'tgl_lahir', 'foto'
    ];

    protected $table = 'siswa';

    // Relasi ke tabel 'kelas'
    public function kelas()
    {
        return $this->belongsTo(Kelas::class)->withDefault();
    }

    // Relasi ke tabel 'ulangan'
    public function ulangan()
    {
        return $this->hasOne(Ulangan::class, 'siswa_id');
    }

    // Relasi ke tabel 'sikap' (banyak data)
    public function sikap()
    {
        return $this->hasMany(Sikap::class, 'siswa_id');
    }

    // Relasi ke tabel 'rapot'
    public function rapot()
    {
        return $this->hasMany(Rapot::class, 'siswa_id');
    }

    // Relasi ke tabel 'mapel' melalui tabel 'rapot'
    public function mapel()
    {
        return $this->hasManyThrough(Mapel::class, Rapot::class, 'siswa_id', 'id', 'id', 'mapel_id');
    }

    // Fungsi untuk mengambil sikap terbaru
    public function sikapTerbaru()
    {
        return $this->hasOne(Sikap::class, 'siswa_id')->latest();
    }
}
