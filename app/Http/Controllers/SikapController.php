<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Mapel;
use App\Guru;
use App\Siswa;
use App\Kelas;
use App\Jadwal;
use App\Sikap;

class SikapController extends Controller
{
    public function index()
    {
        $guru = Guru::where('id_card', Auth::user()->id_card)->first();
        $jadwal = Jadwal::where('guru_id', $guru->id)->orderBy('kelas_id')->get();
        $kelas = $jadwal->groupBy('kelas_id');

        return view('guru.sikap.index', compact('kelas', 'guru'));
    }

    public function create()
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();
        return view('admin.sikap.home', compact('kelas'));
    }

    public function store(Request $request)
    {
        // 🔍 Debug: Cek data request masuk di log
        Log::info('Request Data:', $request->all());
        $request->validate([
            'siswa_id' => 'required|array',
            'siswa_id.*' => 'exists:siswa,id',
            'sikap_1' => 'nullable|array',
            'sikap_2' => 'nullable|array',
            'sikap_3' => 'nullable|array'
        ]);
        

        $guru = Guru::findOrFail($request->guru_id);
        $cekJadwal = Jadwal::where('guru_id', $guru->id)->where('kelas_id', $request->kelas_id)->exists();

        if (!$cekJadwal) {
            return response()->json(['error' => 'Maaf, guru ini tidak mengajar kelas ini!'], 422);
        }

        foreach ($request->siswa_id as $index => $siswaId) {
            Sikap::updateOrCreate(
                [
                    'siswa_id' => $siswaId,
                    'kelas_id' => $request->kelas_id,
                    'guru_id' => $request->guru_id,
                    'mapel_id' => $guru->mapel_id
                ],
                [
                    'sikap_1' => $request->sikap_1[$index] ?? null,
                    'sikap_2' => $request->sikap_2[$index] ?? null,
                    'sikap_3' => $request->sikap_3[$index] ?? null
                ]
            );
        }

        return response()->json(['success' => 'Nilai sikap siswa berhasil ditambahkan!']);
    }

    public function show($id)
    {
        $id = Crypt::decrypt($id);
        $guru = Guru::where('id_card', Auth::user()->id_card)->first();
        $kelas = Kelas::findOrFail($id);
        $siswa = Siswa::where('kelas_id', $id)->get();

        return view('guru.sikap.show', compact('guru', 'kelas', 'siswa'));
    }

    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $kelas = Kelas::findOrFail($id);
        $siswa = Siswa::where('kelas_id', $id)->orderBy('nama_siswa')->get();

        return view('admin.sikap.index', compact('kelas', 'siswa'));
    }

    public function destroy($id)
    {
        $id = Crypt::decrypt($id);
        $sikap = Sikap::findOrFail($id);
        $sikap->delete();

        return response()->json(['success' => 'Data sikap berhasil dihapus']);
    }

    public function sikap($id)
    {
        $id = Crypt::decrypt($id);
        $siswa = Siswa::findOrFail($id);
        $kelas = Kelas::findOrFail($siswa->kelas_id);
        $mapel = Mapel::all();

        return view('admin.sikap.show', compact('mapel', 'siswa', 'kelas'));
    }

    public function siswa()
    {
        $siswa = Siswa::where('no_induk', Auth::user()->no_induk)->first();

        if (!$siswa) {
            return redirect()->back()->with('error', 'Siswa tidak ditemukan.');
        }

        $kelas = Kelas::findOrFail($siswa->kelas_id);
        $mapelList = Mapel::all();
        $sikapData = Sikap::where('siswa_id', $siswa->id)->get()->groupBy('mapel_id');

        return view('siswa.rapot', compact('siswa', 'kelas', 'mapelList', 'sikapData'));
    }
}
