@extends('template_backend.home') 
@section('heading', 'Nilai Rapot')
@section('page')
  <li class="breadcrumb-item active">Nilai Rapot</li>
@endsection
@section('content')
<div class="col-md-12">
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Nilai Rapot Siswa</h3>
      </div>
      @csrf
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
                <table class="table" style="margin-top: -10px;">
                    <tr><td>No Induk Siswa</td><td>:</td><td>{{ Auth::user()->no_induk }}</td></tr>
                    <tr><td>Nama Siswa</td><td>:</td><td class="text-capitalize">{{ Auth::user()->name }}</td></tr>
                    <tr><td>Nama Kelas</td><td>:</td><td>{{ $kelas->nama_kelas }}</td></tr>
                    <tr><td>Wali Kelas</td><td>:</td><td>{{ optional($kelas->guru)->nama_guru ?? '-' }}</td></tr>
                    <tr><td>Semester</td><td>:</td><td>{{ date('m') > 6 ? 'Semester Ganjil' : 'Semester Genap' }}</td></tr>
                    <tr><td>Tahun Pelajaran</td><td>:</td><td>{{ date('m') > 6 ? date('Y').'/'.(date('Y')+1) : (date('Y')-1).'/'.date('Y') }}</td></tr>
                </table>
                <hr>
            </div>

            <h4 class="mb-3">B. Pengetahuan dan Keterampilan</h4>
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th rowspan="2">No.</th>
                        <th rowspan="2">Mata Pelajaran</th>
                        <th rowspan="2">KKM</th>
                        <th class="ctr" colspan="3">Pengetahuan</th>
                        <th class="ctr" colspan="3">Keterampilan</th>
                    </tr>
                    <tr>
                        <th class="ctr">Nilai</th>
                        <th class="ctr">Predikat</th>
                        <th class="ctr">Deskripsi</th>
                        <th class="ctr">Nilai</th>
                        <th class="ctr">Predikat</th>
                        <th class="ctr">Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mapelList as $mapel)
                    @php
                    $rapot = $rapotData[$mapel->id][0] ?? null;
                @endphp                
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $mapel->nama_mapel }}</td>
                        <td>75</td>
                        <td>{{ optional($rapot)->p_nilai ?? '-' }}</td>
                        <td>{{ optional($rapot)->p_predikat ?? '-' }}</td>
                        <td>{{ optional($rapot)->p_deskripsi ?? 'Deskripsi tidak tersedia' }}</td>
                        <td>{{ optional($rapot)->k_nilai ?? '-' }}</td>
                        <td>{{ optional($rapot)->k_predikat ?? '-' }}</td>
                        <td>{{ optional($rapot)->k_deskripsi ?? 'Deskripsi tidak tersedia' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script> $("#RapotSiswa").addClass("active"); </script> 
@endsection
