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
    $siswa = Siswa::where('no_induk', Auth::user()->no_induk)->first();
    if (!$siswa) {
        return back()->with('error', 'Siswa tidak ditemukan.');
    }

    $kelas = Kelas::find($siswa->kelas_id);
    if (!$kelas) {
        return back()->with('error', 'Kelas tidak ditemukan.');
    }

    // Ambil mapel berdasarkan jadwal di kelas siswa
    $mapelList = Jadwal::where('kelas_id', $kelas->id)->with('mapel')->get()->pluck('mapel');

    // Ambil rapot yang sesuai dengan siswa dan mapel_id, lalu dikelompokkan berdasarkan mapel_id
    $rapotData = Rapot::where('siswa_id', $siswa->id)->get()->groupBy('mapel_id');

    return view('siswa.rapot', compact('siswa', 'kelas', 'mapelList', 'rapotData'));
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
    public function Nilai() {
        return $this->hasOne(Nilai::class, 'mapel_id', 'mapel_id');
    }
    
}
