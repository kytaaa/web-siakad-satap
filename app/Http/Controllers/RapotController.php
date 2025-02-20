<?php

namespace App\Http\Controllers;

use App\Guru;
use App\Kelas;
use App\Mapel;
use App\Rapot;
use App\Siswa;
use App\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class RapotController extends Controller
{
    public function index()
    {
        $guru = Guru::where('id_card', Auth::user()->id_card)->first();
        $jadwal = Jadwal::where('guru_id', $guru->id)->orderBy('kelas_id')->get();
        $kelas = $jadwal->groupBy('kelas_id');

        return view('guru.rapot.kelas', compact('kelas', 'guru'));
    }

    public function create()
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();
        return view('admin.rapot.home', compact('kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|integer',
            'kelas_id' => 'required|integer',
            'guru_id' => 'required|integer',
            'nilai_pengetahuan' => 'nullable|numeric|min:0|max:100',
            'nilai_keterampilan' => 'nullable|numeric|min:0|max:100',
        ]);

        $guru = Guru::findOrFail($request->guru_id);
        $cekJadwal = Jadwal::where('guru_id', $guru->id)->where('kelas_id', $request->kelas_id)->count();

        if ($cekJadwal >= 1) {
            $nilaiPengetahuan = $request->nilai_pengetahuan ?? 0;
            $nilaiKeterampilan = $request->nilai_keterampilan ?? 0;

            $predikatPengetahuan = $this->getPredikat($nilaiPengetahuan);
            $predikatKeterampilan = $this->getPredikat($nilaiKeterampilan);

            $deskripsiPengetahuan = $this->getDeskripsi($nilaiPengetahuan);
            $deskripsiKeterampilan = $this->getDeskripsi($nilaiKeterampilan);

            Rapot::updateOrCreate(
                ['siswa_id' => $request->siswa_id, 'mapel_id' => $guru->mapel_id],
                [
                    'kelas_id' => $request->kelas_id,
                    'guru_id' => $request->guru_id,
                    'p_nilai' => $nilaiPengetahuan,
                    'p_predikat' => $predikatPengetahuan,
                    'p_deskripsi' => $deskripsiPengetahuan,
                    'k_nilai' => $nilaiKeterampilan,
                    'k_predikat' => $predikatKeterampilan,
                    'k_deskripsi' => $deskripsiKeterampilan,
                ]
            );

            return response()->json(['success' => 'Nilai rapot siswa berhasil ditambahkan!']);
        } else {
            return response()->json(['error' => 'Maaf, guru ini tidak mengajar kelas ini!']);
        }
    }

    private function getPredikat($nilai)
    {
        if ($nilai >= 90) {
            return 'A';
        } elseif ($nilai >= 80) {
            return 'B';
        } elseif ($nilai >= 70) {
            return 'C';
        } else {
            return 'D';
        }
    }

    private function getDeskripsi($nilai)
    {
        $predikat = $this->getPredikat($nilai);
        
        if ($predikat === 'A') {
            return 'Sangat Baik dalam memahami materi.';
        } elseif ($predikat === 'B') {
            return 'Baik dalam memahami materi.';
        } elseif ($predikat === 'C') {
            return 'Cukup dalam memahami materi.';
        } else {
            return 'Perlu peningkatan dalam memahami materi.';
        }
    }

    public function show($id)
    {
        $id = Crypt::decrypt($id);
        $guru = Guru::where('id_card', Auth::user()->id_card)->first();
        $kelas = Kelas::findOrFail($id);
        $siswa = Siswa::where('kelas_id', $id)->get();
        return view('guru.rapot.rapot', compact('guru', 'kelas', 'siswa'));
    }

    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $kelas = Kelas::findOrFail($id);
        $siswa = Siswa::orderBy('nama_siswa')->where('kelas_id', $id)->get();
        return view('admin.rapot.index', compact('kelas', 'siswa'));
    }

    public function rapot($id)
    {
        $id = Crypt::decrypt($id);
        $siswa = Siswa::findOrFail($id);
        $kelas = Kelas::findOrFail($siswa->kelas_id);
        $jadwal = Jadwal::orderBy('mapel_id')->where('kelas_id', $kelas->id)->get();
        $mapelList = $jadwal->groupBy('mapel_id');
        
        return view('admin.rapot.show', compact('mapelList', 'siswa', 'kelas'));
    }

    public function predikat(Request $request)
    {
        $request->validate([
            'nilai' => 'required|numeric|min:0|max:100',
        ]);

        $predikat = $this->getPredikat($request->nilai);
        $deskripsi = $this->getDeskripsi($request->nilai);

        return response()->json([
            'predikat' => $predikat,
            'deskripsi' => $deskripsi,
        ]);
    }
    
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
    
        $mapelList = Mapel::withTrashed()->get();
        if ($mapelList->isEmpty()) {
            return back()->with('error', 'Tidak ada mata pelajaran yang ditemukan.');
        }
    
        // $sikapData = Sikap::where('siswa_id', $siswa->id)->get()->groupBy('mapel_id');
        $rapotData = Rapot::where('siswa_id', $siswa->id)->get()->groupBy('mapel_id');
    
        return view('siswa.rapot', compact('siswa', 'kelas', 'mapelList','rapotData'));
    }
}