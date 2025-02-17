<?php

namespace App\Http\Controllers;

use App\Guru;
use App\Nilai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NilaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil data guru beserta data nilai (relasi 'dsk')
        $guru = Guru::with('dsk')->where('id_card', Auth::user()->id_card)->first();

        // Jika tidak ditemukan, tampilkan pesan error
        if (!$guru) {
            return redirect()->back()->with('error', 'Data guru tidak ditemukan.');
        }

        // Ambil data nilai berdasarkan guru_id, jika tidak ada buat baru
        $nilai = Nilai::firstOrNew(['guru_id' => $guru->id], [
            'kkm' => 70, // Default nilai KKM
            'deskripsi_a' => '',
            'deskripsi_b' => '',
            'deskripsi_c' => '',
            'deskripsi_d' => '',
        ]);

        return view('guru.nilai', compact('nilai', 'guru'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $guru = Guru::orderBy('kode')->get();
        return view('admin.nilai.index', compact('guru'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'guru_id' => 'required|exists:guru,id',
            'kkm' => 'required|integer|min:0|max:100',
            'deskripsi_a' => 'required',
            'deskripsi_b' => 'required',
            'deskripsi_c' => 'required',
            'deskripsi_d' => 'required',
        ]);
    
        // Ambil guru langsung dari ID yang dikirim
        $guru = Guru::findOrFail($request->guru_id);
    
        Nilai::updateOrCreate(
            ['guru_id' => $guru->id],
            [
                'kkm' => $request->kkm,
                'deskripsi_a' => $request->deskripsi_a,
                'deskripsi_b' => $request->deskripsi_b,
                'deskripsi_c' => $request->deskripsi_c,
                'deskripsi_d' => $request->deskripsi_d,
            ]
        );
    
        return redirect()->back()->with('success', 'Data nilai berhasil diperbarui!');
    }
}
