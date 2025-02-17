<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Guru extends Model
{
    use SoftDeletes;

    protected $fillable = ['id_card', 'nip', 'nama_guru', 'mapel_id', 'kode', 'jk', 'telp', 'tmp_lahir', 'tgl_lahir', 'foto'];

    protected $table = 'guru';

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'mapel_id')->withDefault();
    }

    public function dsk()
    {
        return $this->hasOne(Nilai::class, 'guru_id', 'id');
    }
    

    public function rapot()
    {
        return $this->hasOne(Rapot::class, 'guru_id', 'id');
    }
}
