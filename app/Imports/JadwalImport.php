<?php

namespace App\Imports;

use App\Jadwal;
use App\Hari;
use App\Kelas;
use App\Mapel;
use App\Guru;
use App\Ruang;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class JadwalImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Debugging: Cek apakah data dari Excel sudah masuk dengan benar
        Log::info('Row imported:', $row);

        // Pastikan bahwa semua key sesuai dengan header di Excel
        if (!isset($row['namahari'], $row['namakelas'], $row['mapel'], $row['namaguru'], $row['jammulai'], $row['jamselesai'], $row['namaruang'])) {
            Log::error('Header tidak sesuai atau ada yang kosong', $row);
            return null;
        }

        $hari = Hari::where('nama_hari', trim($row['namahari']))->first();
        $kelas = Kelas::where('nama_kelas', trim($row['namakelas']))->first();
        $mapel = Mapel::where('nama_mapel', trim($row['mapel']))->first();
        $guru = Guru::where('nama_guru', trim($row['namaguru']))->first();
        $ruang = Ruang::where('nama_ruang', trim($row['namaruang']))->first();

        // Cek apakah semua data ditemukan
        if (!$hari || !$kelas || !$mapel || !$guru || !$ruang) {
            Log::error('Data tidak ditemukan untuk:', [
                'hari' => $row['namahari'] ?? 'NULL',
                'kelas' => $row['namakelas'] ?? 'NULL',
                'mapel' => $row['mapel'] ?? 'NULL',
                'guru' => $row['namaguru'] ?? 'NULL',
                'ruang' => $row['namaruang'] ?? 'NULL'
            ]);
            return null; // Lewati baris ini jika ada yang tidak ditemukan
        }

        return new Jadwal([
            'hari_id' => $hari->id,
            'kelas_id' => $kelas->id,
            'mapel_id' => $mapel->id,
            'guru_id' => $guru->id,
            'jam_mulai' => $this->convertTime($row['jammulai']),
            'jam_selesai' => $this->convertTime($row['jamselesai']),
            'ruang_id' => $ruang->id,
        ]);
    }

    /**
     * Konversi format waktu dari Excel ke format HH:MM:SS
     */
    private function convertTime($excelTime)
    {
        if (is_numeric($excelTime)) {
            return Date::excelToDateTimeObject($excelTime)->format('H:i:s');
        }
        return $excelTime; // Jika formatnya sudah benar, tidak perlu dikonversi
    }
}
