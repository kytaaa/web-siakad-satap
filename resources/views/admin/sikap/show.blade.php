@extends('template_backend.home')
@section('heading', 'Show Nilai Sikap')
@section('page')
  <li class="breadcrumb-item active">Show Nilai Sikap</li>
@endsection
@section('content')
<div class="col-md-12">
    <!-- general form elements -->
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Show Nilai Sikap</h3>
      </div>
      <!-- /.card-header -->
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
                <table class="table" style="margin-top: -10px;">
                    <tr>
                        <td>No Induk Siswa</td>
                        <td>:</td>
                        <td>{{ $siswa->no_induk ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Nama Siswa</td>
                        <td>:</td>
                        <td>{{ $siswa->nama_siswa ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Nama Kelas</td>
                        <td>:</td>
                        <td>{{ $kelas->first()->nama_kelas ?? 'Tidak ada kelas' }}</td>
                    </tr>
                    <tr>
                        <td>Wali Kelas</td>
                        <td>:</td>
                        <td>{{ optional($kelas->first()->guru)->nama_guru ?? 'Tidak ada wali kelas' }}</td>
                    </tr>
                    @php
                        $bulan = date('m');
                        $tahun = date('Y');
                    @endphp
                    <tr>
                        <td>Semester</td>
                        <td>:</td>
                        <td>
                            {{ $bulan > 6 ? 'Semester Ganjil' : 'Semester Genap' }}
                        </td>
                    </tr>
                    <tr>
                        <td>Tahun Pelajaran</td>
                        <td>:</td>
                        <td>
                            {{ $bulan > 6 ? $tahun . '/' . ($tahun+1) : ($tahun-1) . '/' . $tahun }}
                        </td>
                    </tr>
                </table>
                <hr>
            </div>
            <div class="col-md-12">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th rowspan="2" class="ctr">No.</th>
                            <th rowspan="2">Nama Mapel</th>
                            <th colspan="3" class="ctr">Nilai Sikap</th>
                        </tr>
                        <tr>
                            <th class="ctr">Teman</th>
                            <th class="ctr">Sendiri</th>
                            <th class="ctr">Guru</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mapel as $data)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $data->nama_mapel }}</td>
                                @php
                                    $array = ['mapel' => $data->id, 'siswa' => $siswa->id ?? null];
                                    $jsonData = json_encode($array);
                                    $nilaiSikap = $data->cekSikap($jsonData) ?? ['sikap_1' => '-', 'sikap_2' => '-', 'sikap_3' => '-'];
                                @endphp
                                <td class="ctr">{{ $nilaiSikap['sikap_1'] }}</td>
                                <td class="ctr">{{ $nilaiSikap['sikap_2'] }}</td>
                                <td class="ctr">{{ $nilaiSikap['sikap_3'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada data mata pelajaran</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
          </div>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>
@endsection
@section('script')
    <script>
        $("#Nilai").addClass("active");
        $("#liNilai").addClass("menu-open");
        $("#Sikap").addClass("active");
    </script>
@endsection
