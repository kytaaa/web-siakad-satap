<?php

namespace App\Http\Controllers;

use Auth;
use App\Guru;
use App\Siswa;
use App\Kelas;
use App\Jadwal;
use App\Ulangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class UlanganController extends Controller
{
    public function index()
    {
        $guru = Guru::where('id_card', Auth::user()->id_card)->firstOrFail();
        $jadwal = Jadwal::where('guru_id', $guru->id)->orderBy('kelas_id')->get();
        $kelas = $jadwal->groupBy('kelas_id');

        return view('guru.ulangan.kelas', compact('kelas', 'guru'));
    }

    public function create()
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();
        return view('admin.ulangan.home', compact('kelas'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'siswa_id' => 'required|exists:siswa,id',
            'kelas_id' => 'required|exists:kelas,id',
            'guru_id'  => 'required|exists:guru,id',
            'ulha_1'   => 'nullable|integer|min:0|max:100',
            'ulha_2'   => 'nullable|integer|min:0|max:100',
            'uts'      => 'nullable|integer|min:0|max:100',
            'ulha_3'   => 'nullable|integer|min:0|max:100',
            'uas'      => 'nullable|integer|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            $guru = Guru::findOrFail($request->guru_id);
            
            // Update atau buat data baru berdasarkan siswa_id, kelas_id, dan guru_id
            $ulangan = Ulangan::updateOrCreate(
                [
                    'siswa_id' => $request->siswa_id,
                    'kelas_id' => $request->kelas_id,
                    'guru_id'  => $request->guru_id,
                ],
                [
                    'mapel_id' => $guru->mapel_id,
                    'ulha_1'   => $request->ulha_1,
                    'ulha_2'   => $request->ulha_2,
                    'uts'      => $request->uts,
                    'ulha_3'   => $request->ulha_3,
                    'uas'      => $request->uas,
                ]
            );

            // Cek apakah data tersimpan dengan benar
            if ($ulangan) {
                return response()->json(['success' => 'Nilai ulangan siswa berhasil diperbarui!', 'data' => $ulangan]);
            } else {
                return response()->json(['error' => 'Gagal menyimpan data.'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $id = Crypt::decrypt($id);
        $guru = Guru::where('id_card', Auth::user()->id_card)->firstOrFail();
        $kelas = Kelas::findOrFail($id);
        $siswa = Siswa::where('kelas_id', $id)->get();

        return view('guru.ulangan.nilai', compact('guru', 'kelas', 'siswa'));
    }

    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $kelas = Kelas::findOrFail($id);
        $siswa = Siswa::where('kelas_id', $id)->orderBy('nama_siswa')->get();

        return view('admin.ulangan.index', compact('kelas', 'siswa'));
    }

    public function update(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        
        // Validasi input
        $validator = Validator::make($request->all(), [
            'ulha_1' => 'nullable|integer|min:0|max:100',
            'ulha_2' => 'nullable|integer|min:0|max:100',
            'uts'    => 'nullable|integer|min:0|max:100',
            'ulha_3' => 'nullable|integer|min:0|max:100',
            'uas'    => 'nullable|integer|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $ulangan = Ulangan::findOrFail($id);
            $ulangan->update($request->only(['ulha_1', 'ulha_2', 'uts', 'ulha_3', 'uas']));

            return redirect()->back()->with('success', 'Data berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $id = Crypt::decrypt($id);
            Ulangan::findOrFail($id)->delete();

            return redirect()->back()->with('success', 'Data berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function ulangan($id)
    {
        $id = Crypt::decrypt($id);
        $siswa = Siswa::findOrFail($id);
        $kelas = Kelas::findOrFail($siswa->kelas_id);
        $jadwal = Jadwal::where('kelas_id', $kelas->id)->orderBy('mapel_id')->get();
        $mapel = $jadwal->groupBy('mapel_id');

        return view('admin.ulangan.show', compact('mapel', 'siswa', 'kelas'));
    }

    public function siswa()
    {
        $siswa = Siswa::where('no_induk', Auth::user()->no_induk)->firstOrFail();
        $kelas = Kelas::findOrFail($siswa->kelas_id);
        $jadwal = Jadwal::where('kelas_id', $kelas->id)->orderBy('mapel_id')->get();
        $mapel = $jadwal->groupBy('mapel_id');

        // Ambil nilai ulangan siswa
        $nilai = Ulangan::where('siswa_id', $siswa->id)->get();

        return view('siswa.ulangan', compact('siswa', 'kelas', 'mapel', 'nilai'));
    }
}
