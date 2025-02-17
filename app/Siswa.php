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
        return $this->belongsTo('App\Kelas')->withDefault();
    }

    // Relasi ke tabel 'ulangan'
    public function ulangan()
    {
        return $this->hasOne('App\Ulangan', 'siswa_id');
    }

    // Relasi ke tabel 'sikap' (banyak data)
    public function sikap()
    {
        return $this->hasMany('App\Sikap', 'siswa_id');
    }

    // Relasi ke tabel 'rapot'
    public function nilai()
    {
        return $this->hasOne('App\Rapot', 'siswa_id');
    }

    // Fungsi untuk mengambil sikap terbaru
    public function sikapTerbaru()
    {
        return $this->hasOne('App\Sikap', 'siswa_id')->latest();
    }
}
