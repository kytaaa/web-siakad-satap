<?php

namespace App\Http\Controllers;

use PDF;
use App\User;
use App\Kelas;
use App\Siswa;
use App\Exports\SiswaExport;
use App\Imports\SiswaImport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Crypt;

class SiswaController extends Controller
{
    public function index()
    {
        $kelas = Kelas::orderBy('nama_kelas', 'asc')->get();
        return view('admin.siswa.index', compact('kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_induk' => 'required|string|unique:siswa',
            'nama_siswa' => 'required',
            'jk' => 'required',
            'kelas_id' => 'required'
        ]);

        $fotoPath = $request->foto ? $request->foto->store('uploads/siswa') : 
            ($request->jk == 'L' ? 'uploads/siswa/default_male.jpg' : 'uploads/siswa/default_female.jpg');

        Siswa::create(array_merge($request->all(), ['foto' => $fotoPath]));

        return back()->with('success', 'Berhasil menambahkan data siswa baru!');
    }

    public function show($id)
    {
        $siswa = Siswa::findOrFail(Crypt::decrypt($id));
        return view('admin.siswa.details', compact('siswa'));
    }

    public function edit($id)
    {
        $siswa = Siswa::findOrFail(Crypt::decrypt($id));
        $kelas = Kelas::all();
        return view('admin.siswa.edit', compact('siswa', 'kelas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_siswa' => 'required',
            'jk' => 'required',
            'kelas_id' => 'required'
        ]);

        $siswa = Siswa::findOrFail($id);
        User::where('no_induk', $siswa->no_induk)->update(['name' => $request->nama_siswa]);
        $siswa->update($request->all());

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $siswa = Siswa::findOrFail($id);
        User::where('no_induk', $siswa->no_induk)->delete();
        $siswa->delete();
        return back()->with('warning', 'Data siswa berhasil dihapus!');
    }

    public function cetak_pdf(Request $request)
    {
        $siswa = Siswa::where('kelas_id', $request->id)->orderBy('nama_siswa', 'asc')->get();
        $kelas = Kelas::findOrFail($request->id);
        $pdf = PDF::loadView('siswa-pdf', compact('siswa', 'kelas'));
        return $pdf->stream();
    }

    public function import_excel(Request $request)
    {
        $request->validate(['file' => 'required|mimes:csv,xls,xlsx']);
        $filePath = $request->file('file')->store('file_siswa');
        Excel::import(new SiswaImport, storage_path('app/' . $filePath));
        return back()->with('success', 'Data Siswa Berhasil Diimport!');
    }
}
