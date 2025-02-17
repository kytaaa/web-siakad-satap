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
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <table class="table">
                        <tr>
                            <td>No Induk Siswa</td>
                            <td>:</td>
                            <td>{{ Auth::user()->no_induk }}</td>
                        </tr>
                        <tr>
                            <td>Nama Siswa</td>
                            <td>:</td>
                            <td class="text-capitalize">{{ Auth::user()->name }}</td>
                        </tr>
                        <tr>
                            <td>Nama Kelas</td>
                            <td>:</td>
                            <td>{{ $kelas->nama_kelas }}</td>
                        </tr>
                        <tr>
                            <td>Wali Kelas</td>
                            <td>:</td>
                            <td>{{ $kelas->guru->nama_guru }}</td>
                        </tr>
                        <tr>
                            <td>Semester</td>
                            <td>:</td>
                            <td>{{ now()->month > 6 ? 'Semester Ganjil' : 'Semester Genap' }}</td>
                        </tr>
                        <tr>
                            <td>Tahun Pelajaran</td>
                            <td>:</td>
                            <td>
                                {{ now()->month > 6 ? now()->year . '/' . (now()->year + 1) : (now()->year - 1) . '/' . now()->year }}
                            </td>
                        </tr>
                    </table>
                    <hr>
                </div>
                <div class="col-md-12">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="ctr">No.</th>
                                <th>Mata Pelajaran</th>
                                <th class="ctr">ULHA 1</th>
                                <th class="ctr">ULHA 2</th>
                                <th class="ctr">UTS</th>
                                <th class="ctr">ULHA 3</th>
                                <th class="ctr">UAS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mapel as $index => $data)
                                @php $nilai = $data->first(); @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $nilai->mapel->nama_mapel }}</td>
                                    <td class="ctr">{{ optional($nilai->ulangan($index))['ulha_1'] ?? '-' }}</td>
                                    <td class="ctr">{{ optional($nilai->ulangan($index))['ulha_2'] ?? '-' }}</td>
                                    <td class="ctr">{{ optional($nilai->ulangan($index))['uts'] ?? '-' }}</td>
                                    <td class="ctr">{{ optional($nilai->ulangan($index))['ulha_3'] ?? '-' }}</td>
                                    <td class="ctr">{{ optional($nilai->ulangan($index))['uas'] ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script>
        $("#UlanganSiswa").addClass("active");
    </script>
@endsection
