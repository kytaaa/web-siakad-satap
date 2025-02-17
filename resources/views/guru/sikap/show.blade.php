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
                                        <option value="A" {{ $sikapTerbaru->sikap_1 == 'A' ? 'selected' : '' }}>A</option>
                                        <option value="B" {{ $sikapTerbaru->sikap_1 == 'B' ? 'selected' : '' }}>B</option>
                                        <option value="C" {{ $sikapTerbaru->sikap_1 == 'C' ? 'selected' : '' }}>C</option>
                                        <option value="D" {{ $sikapTerbaru->sikap_1 == 'D' ? 'selected' : '' }}>D</option>
                                        <option value="E" {{ $sikapTerbaru->sikap_1 == 'E' ? 'selected' : '' }}>E</option>
                                    </select>
                                </td>
                                <td class="ctr">
                                    <select name="sikap_2[]" class="form-control">
                                        <option value="A" {{ $sikapTerbaru->sikap_2 == 'A' ? 'selected' : '' }}>A</option>
                                        <option value="B" {{ $sikapTerbaru->sikap_2 == 'B' ? 'selected' : '' }}>B</option>
                                        <option value="C" {{ $sikapTerbaru->sikap_2 == 'C' ? 'selected' : '' }}>C</option>
                                        <option value="D" {{ $sikapTerbaru->sikap_2 == 'D' ? 'selected' : '' }}>D</option>
                                        <option value="E" {{ $sikapTerbaru->sikap_2 == 'E' ? 'selected' : '' }}>E</option>
                                    </select>
                                </td>
                                <td class="ctr">
                                    <select name="sikap_3[]" class="form-control">
                                        <option value="A" {{ $sikapTerbaru->sikap_3 == 'A' ? 'selected' : '' }}>A</option>
                                        <option value="B" {{ $sikapTerbaru->sikap_3 == 'B' ? 'selected' : '' }}>B</option>
                                        <option value="C" {{ $sikapTerbaru->sikap_3 == 'C' ? 'selected' : '' }}>C</option>
                                        <option value="D" {{ $sikapTerbaru->sikap_3 == 'D' ? 'selected' : '' }}>D</option>
                                        <option value="E" {{ $sikapTerbaru->sikap_3 == 'E' ? 'selected' : '' }}>E</option>
                                    </select>
                                </td>
                                <td class="ctr">
                                    <button type="button" class="btn btn-success btn-save" data-id="{{ $data->id }}">
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
            let row = $(this).closest('tr');
            let siswa_id = row.find('input[name="siswa_id[]"]').val();
            let sikap_1 = row.find('select[name="sikap_1[]"]').val();
            let sikap_2 = row.find('select[name="sikap_2[]"]').val();
            let sikap_3 = row.find('select[name="sikap_3[]"]').val();
            let guru_id = $('input[name="guru_id"]').val();
            let kelas_id = $('input[name="kelas_id"]').val();

            $.ajax({
                url: "{{ route('sikap.store') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    _token: '{{ csrf_token() }}',
                    siswa_id: siswa_id,
                    kelas_id: kelas_id,
                    guru_id: guru_id,
                    sikap_1: sikap_1,
                    sikap_2: sikap_2,
                    sikap_3: sikap_3
                },
                beforeSend: function() {
                    row.find('.btn-save').html('<i class="fas fa-spinner fa-spin"></i>');
                },
                success: function(response) {
                    toastr.success("Nilai sikap siswa berhasil disimpan!");
                    row.find('.btn-save').html('<i class="fas fa-check"></i>').prop('disabled', true);
                },
                error: function(xhr) {
                    let errorMsg = xhr.responseJSON?.error || "Terjadi kesalahan!";
                    toastr.error(errorMsg);
                    row.find('.btn-save').html('<i class="fas fa-save"></i>');
                }
            });
        });
    });

    $("#NilaiGuru").addClass("active");
    $("#liNilaiGuru").addClass("menu-open");
    $("#SikapGuru").addClass("active");
</script>
@endsection
