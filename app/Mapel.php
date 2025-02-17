<?php

namespace App;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mapel extends Model
{
    use SoftDeletes;

    protected $fillable = ['id', 'nama_mapel', 'paket_id', 'kelompok'];

    public function paket()
    {
        return $this->belongsTo('App\Paket')->withDefault();
    }

    public function sikap($id)
    {
        $siswa = Siswa::where('no_induk', Auth::user()->no_induk)->first();
        $nilai = Sikap::where('siswa_id', $siswa->id)->where('mapel_id', $id)->first();
        return $nilai;
    }

    public function cekSikap($id)
    {
        $data = json_decode($id, true);
        $sikap = Sikap::where('siswa_id', $data['siswa'])->where('mapel_id', $data['mapel'])->first();
        return $sikap;
    }
    public function rapot()
    {
        return $this->hasMany('App\Rapot', 'mapel_id');
    }
    
    public function predikat()
{
    return $this->hasOne('App\Predikat', 'mapel_id');
}

    protected $table = 'mapel';
}
