@extends('template_backend.home')
@section('heading', 'Entry Nilai Ulangan')
@section('page')
  <li class="breadcrumb-item active">Entry Nilai Ulangan</li>
@endsection

@section('content')
<div class="col-md-12">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Entry Nilai Ulangan</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <table class="table">
                        <tr><td>Nama Kelas</td><td>:</td><td>{{ $kelas->nama_kelas ?? '-' }}</td></tr>
                        <tr><td>Wali Kelas</td><td>:</td><td>{{ optional($kelas->guru)->nama_guru ?? '-' }}</td></tr>
                        <tr><td>Jumlah Siswa</td><td>:</td><td>{{ $siswa->count() }}</td></tr>
                        <tr><td>Mata Pelajaran</td><td>:</td><td>{{ optional($guru->mapel)->nama_mapel ?? '-' }}</td></tr>
                        <tr><td>Guru Mata Pelajaran</td><td>:</td><td>{{ $guru->nama_guru ?? '-' }}</td></tr>
                        <tr><td>Semester</td><td>:</td><td>{{ date('m') > 6 ? 'Semester Ganjil' : 'Semester Genap' }}</td></tr>
                        <tr><td>Tahun Pelajaran</td><td>:</td><td>{{ date('Y') }}/{{ date('Y')+1 }}</td></tr>
                    </table>
                    <hr>
                </div>
                
                <div class="col-md-12">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Siswa</th>
                                <th>ULHA 1</th>
                                <th>ULHA 2</th>
                                <th>UTS</th>
                                <th>ULHA 3</th>
                                <th>UAS</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($siswa as $index => $data)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $data->nama_siswa }}</td>
                                <td><input type="number" class="form-control nilai" data-id="{{ $data->id }}" data-field="ulha_1" value="{{ optional($data->ulangan)->ulha_1 ?? '' }}" min="0" max="100"></td>
                                <td><input type="number" class="form-control nilai" data-id="{{ $data->id }}" data-field="ulha_2" value="{{ optional($data->ulangan)->ulha_2 ?? '' }}" min="0" max="100"></td>
                                <td><input type="number" class="form-control nilai" data-id="{{ $data->id }}" data-field="uts" value="{{ optional($data->ulangan)->uts ?? '' }}" min="0" max="100"></td>
                                <td><input type="number" class="form-control nilai" data-id="{{ $data->id }}" data-field="ulha_3" value="{{ optional($data->ulangan)->ulha_3 ?? '' }}" min="0" max="100"></td>
                                <td><input type="number" class="form-control nilai" data-id="{{ $data->id }}" data-field="uas" value="{{ optional($data->ulangan)->uas ?? '' }}" min="0" max="100"></td>
                                <td>
                                    <button class="btn btn-primary btn-save" data-id="{{ $data->id }}">Simpan</button>
                                </td>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<script>
    $(document).ready(function() {
        $(".btn-save").click(function() {
            var id = $(this).data('id');
            var kelas_id = "{{ $kelas->id }}";
            var guru_id = "{{ $guru->id }}";

            var ulha_1 = $("input[data-id='" + id + "'][data-field='ulha_1']").val();
            var ulha_2 = $("input[data-id='" + id + "'][data-field='ulha_2']").val();
            var uts = $("input[data-id='" + id + "'][data-field='uts']").val();
            var ulha_3 = $("input[data-id='" + id + "'][data-field='ulha_3']").val();
            var uas = $("input[data-id='" + id + "'][data-field='uas']").val();

            // Validasi nilai harus dalam rentang 0-100
            if (ulha_1 < 0 || ulha_1 > 100 || ulha_2 < 0 || ulha_2 > 100 || 
                uts < 0 || uts > 100 || ulha_3 < 0 || ulha_3 > 100 || uas < 0 || uas > 100) {
                toastr.error("Nilai harus antara 0 - 100!");
                return;
            }

            $.ajax({
                url: "{{ route('ulangan.store') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    _token: '{{ csrf_token() }}',
                    siswa_id: id,
                    kelas_id: kelas_id,
                    guru_id: guru_id,
                    ulha_1: ulha_1,
                    ulha_2: ulha_2,
                    uts: uts,
                    ulha_3: ulha_3,
                    uas: uas
                },
                success: function(response) {
                    toastr.success("Nilai berhasil diperbarui!");
                },
                error: function(xhr) {
                    var errorMessage = xhr.responseJSON ? xhr.responseJSON.error : "Terjadi kesalahan saat menyimpan nilai.";
                    toastr.error(errorMessage);
                }
            });
        });
    });
</script>
@endsection
