<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sikap extends Model
{
    protected $table = 'sikap';

    protected $fillable = ['siswa_id', 'kelas_id', 'guru_id', 'mapel_id', 'sikap_1', 'sikap_2', 'sikap_3', 'nilai_akhir'];

    public $timestamps = true;

    protected $casts = [
        'sikap_1' => 'float',
        'sikap_2' => 'float',
        'sikap_3' => 'float',
        'nilai_akhir' => 'float'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'mapel_id');
    }

    // Event untuk otomatis menghitung nilai_akhir sebelum menyimpan
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($sikap) {
            $sikap->nilai_akhir = self::hitungNilaiAkhir($sikap);
        });
    }

    private static function hitungNilaiAkhir($sikap)
    {
        $sikap1 = is_numeric($sikap->sikap_1) ? floatval($sikap->sikap_1) : null;
        $sikap2 = is_numeric($sikap->sikap_2) ? floatval($sikap->sikap_2) : null;
        $sikap3 = is_numeric($sikap->sikap_3) ? floatval($sikap->sikap_3) : null;

        // Jika semua nilai kosong, set nilai_akhir ke null
        if (is_null($sikap1) && is_null($sikap2) && is_null($sikap3)) {
            return null;
        }

        // Hitung rata-rata dari nilai yang tidak null
        $nilaiValid = array_filter([$sikap1, $sikap2, $sikap3], function ($value) {
            return !is_null($value);
        });

        return round(array_sum($nilaiValid) / count($nilaiValid), 2);
    }
}
