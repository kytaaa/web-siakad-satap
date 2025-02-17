<?php

namespace App\Imports;

use App\Siswa;
use App\Kelas;
use Maatwebsite\Excel\Concerns\ToModel;

class SiswaImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Pastikan bahwa row memiliki minimal 4 elemen
        if (!isset($row[3])) {
            return null; // Jika tidak ada, hentikan proses untuk menghindari error
        }

        // Cari kelas berdasarkan nama_kelas di kolom 3
        $kelas = Kelas::where('nama_kelas', $row[3])->first();

        // Jika kelas tidak ditemukan, return null atau atur nilai default
        if (!$kelas) {
            return null; // Bisa juga diganti dengan nilai default kelas_id
        }

        // Tentukan foto berdasarkan jenis kelamin
        $foto = ($row[2] == 'L') 
            ? 'uploads/siswa/52471919042020_male.jpg' 
            : 'uploads/siswa/50271431012020_female.jpg';

        return new Siswa([
            'nama_siswa' => $row[0] ?? '',  // Gunakan default jika null
            'no_induk' => $row[1] ?? '',    // Gunakan default jika null
            'jk' => $row[2] ?? '',          // Gunakan default jika null
            'foto' => $foto,
            'kelas_id' => $kelas->id,       // Pastikan tidak null
        ]);
    }
}
