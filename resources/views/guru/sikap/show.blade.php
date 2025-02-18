@extends('template_backend.home')
@section('heading', 'Entry Nilai Sikap')
@section('page')
  <li class="breadcrumb-item active">Entry Nilai Sikap</li>
@endsection
@section('content')
<div class="col-md-12">
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Entry Nilai Sikap</h3>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-12">
            <table class="table">
                <tr><td>Nama Kelas</td><td>:</td><td>{{ $kelas->nama_kelas ?? 'Tidak ada kelas' }}</td></tr>
                <tr><td>Wali Kelas</td><td>:</td><td>{{ $kelas->guru->nama_guru ?? '-' }}</td></tr>
                <tr><td>Jumlah Siswa</td><td>:</td><td>{{ $siswa->count() }}</td></tr>
                <tr><td>Mata Pelajaran</td><td>:</td><td>{{ $guru->mapel->nama_mapel ?? '-' }}</td></tr>
                <tr><td>Guru Mata Pelajaran</td><td>:</td><td>{{ $guru->nama_guru ?? '-' }}</td></tr>
                @php
                    $bulan = date('m');
                    $tahun = date('Y');
                    $semester = $bulan > 6 ? 'Semester Genap' : 'Semester Ganjil';
                    $tahunAjaran = $bulan > 6 ? "$tahun/".($tahun+1) : ($tahun-1)."/$tahun";
                @endphp
                <tr><td>Semester</td><td>:</td><td>{{ $semester }}</td></tr>
                <tr><td>Tahun Pelajaran</td><td>:</td><td>{{ $tahunAjaran }}</td></tr>
            </table>
            <hr>
          </div>
          <div class="col-md-12">
            <form id="sikapForm">
                @csrf
                <input type="hidden" name="guru_id" value="{{ $guru->id }}">
                <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Siswa</th>
                            <th class="ctr">Teman</th>
                            <th class="ctr">Sendiri</th>
                            <th class="ctr">Guru</th>
                            <th class="ctr">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($siswa as $index => $data)
                            @php
                                $sikapTerbaru = optional($data->sikap->sortByDesc('updated_at')->first());
                            @endphp
                            <tr>
                                <td class="ctr">{{ $index + 1 }}</td>
                                <td>{{ $data->nama_siswa }}</td>
                                <input type="hidden" name="siswa_id[]" value="{{ $data->id }}">
                                <td class="ctr">
                                    <select name="sikap_1[]" class="form-control">
                                        <option value="A" {{ optional($sikapTerbaru)->sikap_1 == 'A' ? 'selected' : '' }}>A</option>
                                        <option value="B" {{ optional($sikapTerbaru)->sikap_1 == 'B' ? 'selected' : '' }}>B</option>
                                        <option value="C" {{ optional($sikapTerbaru)->sikap_1 == 'C' ? 'selected' : '' }}>C</option>
                                        <option value="D" {{ optional($sikapTerbaru)->sikap_1 == 'D' ? 'selected' : '' }}>D</option>
                                        <option value="E" {{ optional($sikapTerbaru)->sikap_1 == 'E' ? 'selected' : '' }}>E</option>
                                    </select>
                                </td>
                                <td class="ctr">
                                    <select name="sikap_2[]" class="form-control">
                                        <option value="A" {{ optional($sikapTerbaru)->sikap_2 == 'A' ? 'selected' : '' }}>A</option>
                                        <option value="B" {{ optional($sikapTerbaru)->sikap_2 == 'B' ? 'selected' : '' }}>B</option>
                                        <option value="C" {{ optional($sikapTerbaru)->sikap_2 == 'C' ? 'selected' : '' }}>C</option>
                                        <option value="D" {{ optional($sikapTerbaru)->sikap_2 == 'D' ? 'selected' : '' }}>D</option>
                                        <option value="E" {{ optional($sikapTerbaru)->sikap_2 == 'E' ? 'selected' : '' }}>E</option>
                                    </select>
                                </td>
                                <td class="ctr">
                                    <select name="sikap_3[]" class="form-control">
                                        <option value="A" {{ optional($sikapTerbaru)->sikap_3 == 'A' ? 'selected' : '' }}>A</option>
                                        <option value="B" {{ optional($sikapTerbaru)->sikap_3 == 'B' ? 'selected' : '' }}>B</option>
                                        <option value="C" {{ optional($sikapTerbaru)->sikap_3 == 'C' ? 'selected' : '' }}>C</option>
                                        <option value="D" {{ optional($sikapTerbaru)->sikap_3 == 'D' ? 'selected' : '' }}>D</option>
                                        <option value="E" {{ optional($sikapTerbaru)->sikap_3 == 'E' ? 'selected' : '' }}>E</option>
                                    </select>
                                </td>
                                <td class="ctr">
                                    <button type="button" class="btn btn-success btn-save">
                                        <i class="fas fa-save"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </form>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    $('.btn-save').click(function() {
    let siswa_id = $('input[name="siswa_id[]"]').map(function() { return $(this).val(); }).get();
    let sikap_1 = $('select[name="sikap_1[]"]').map(function() { return $(this).val(); }).get();
    let sikap_2 = $('select[name="sikap_2[]"]').map(function() { return $(this).val(); }).get();
    let sikap_3 = $('select[name="sikap_3[]"]').map(function() { return $(this).val(); }).get();

    console.log({
        _token: '{{ csrf_token() }}',
        siswa_id: siswa_id,
        sikap_1: sikap_1,
        sikap_2: sikap_2,
        sikap_3: sikap_3
    });

    $.post("{{ route('sikap.store') }}", {
        _token: '{{ csrf_token() }}',
        siswa_id: siswa_id,
        sikap_1: sikap_1,
        sikap_2: sikap_2,
        sikap_3: sikap_3
    }).done(function() {
        toastr.success("Nilai sikap siswa berhasil disimpan!");
    }).fail(function(xhr) {
        console.error(xhr.responseText);
        toastr.error("Terjadi kesalahan saat menyimpan!");
    });
});

});
</script>
@endsection
