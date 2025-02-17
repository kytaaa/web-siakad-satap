@extends('template_backend.home')
@section('heading', 'Entry Nilai Rapot')
@section('page')
<li class="breadcrumb-item active">Entry Nilai Rapot</li>
@endsection
@section('content')
<div class="col-md-12">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Entry Nilai Rapot</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <table class="table">
                        <tr><td>Nama Kelas</td><td>:</td><td>{{ $kelas->nama_kelas }}</td></tr>
                        <tr><td>Wali Kelas</td><td>:</td><td>{{ $kelas->guru->nama_guru }}</td></tr>
                        <tr><td>Jumlah Siswa</td><td>:</td><td>{{ $siswa->count() }}</td></tr>
                        <tr><td>Mata Pelajaran</td><td>:</td><td>{{ $guru->mapel->nama_mapel }}</td></tr>
                        <tr><td>Guru Mata Pelajaran</td><td>:</td><td>{{ $guru->nama_guru }}</td></tr>
                        <tr><td>Semester</td><td>:</td><td>{{ date('m') > 6 ? 'Semester Ganjil' : 'Semester Genap' }}</td></tr>
                        <tr><td>Tahun Pelajaran</td><td>:</td><td>{{ date('m') > 6 ? date('Y').'/'.(date('Y')+1) : (date('Y')-1).'/'.date('Y') }}</td></tr>
                    </table>
                    <hr>
                </div>
                <div class="col-md-12">
                    <form id="formRapot">
                        @csrf
                        <input type="hidden" name="guru_id" value="{{ $guru->id }}">
                        <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama Siswa</th>
                                    <th>Nilai Pengetahuan</th>
                                    <th>Predikat</th>
                                    <th>Deskripsi</th>
                                    <th>Nilai Keterampilan</th>
                                    <th>Predikat</th>
                                    <th>Deskripsi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($siswa as $data)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $data->nama_siswa }}</td>
                                        <td><input type="number" name="nilai_pengetahuan[{{ $data->id }}]" class="form-control nilai" min="0" max="100" data-id="{{ $data->id }}"></td>
                                        <td><input type="text" name="predikat_pengetahuan[{{ $data->id }}]" class="form-control predikat" readonly></td>
                                        <td><textarea name="deskripsi_pengetahuan[{{ $data->id }}]" class="form-control deskripsi" readonly></textarea></td>
                                        <td><input type="number" name="nilai_keterampilan[{{ $data->id }}]" class="form-control nilai" min="0" max="100" data-id="{{ $data->id }}"></td>
                                        <td><input type="text" name="predikat_keterampilan[{{ $data->id }}]" class="form-control predikat" readonly></td>
                                        <td><textarea name="deskripsi_keterampilan[{{ $data->id }}]" class="form-control deskripsi" readonly></textarea></td>
                                        <td>
                                            <button type="button" class="btn btn-success btn_save" data-id="{{ $data->id }}"><i class="fas fa-save"></i></button>
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
    $(document).on("keyup", ".nilai", function() {
        var id = $(this).data("id");
        var nilai = $(this).val();
        
        if (nilai < 0 || nilai > 100) {
            Swal.fire('Error', 'Nilai harus antara 0 - 100', 'error');
            $(this).val("");
            return;
        }

        var fieldType = $(this).attr("name").includes("pengetahuan") ? "pengetahuan" : "keterampilan";

        $.ajax({
            type: "GET",
            url: "{{ url('/rapot/predikat') }}",
            data: { nilai: nilai },
            dataType: "JSON",
            success: function(data) {
                console.log("Success Response: ", data);
                if (data.predikat) {
                    $("input[name='predikat_" + fieldType + "[" + id + "]']").val(data.predikat);
                    $("textarea[name='deskripsi_" + fieldType + "[" + id + "]']").val(data.deskripsi);
                }
            },
            error: function(xhr) {
                console.log("Error Response: ", xhr);
                Swal.fire('Error', 'Terjadi kesalahan: ' + xhr.responseText, 'error');
            }
        });
    });

    $(document).on("click", ".btn_save", function() {
        var id = $(this).data("id");
        var formData = {
            _token: $('input[name="_token"]').val(),
            siswa_id: id,
            guru_id: $('input[name="guru_id"]').val(),
            kelas_id: $('input[name="kelas_id"]').val(),
            nilai_pengetahuan: $("input[name='nilai_pengetahuan["+id+"]']").val(),
            predikat_pengetahuan: $("input[name='predikat_pengetahuan["+id+"]']").val(),
            deskripsi_pengetahuan: $("textarea[name='deskripsi_pengetahuan["+id+"]']").val(),
            nilai_keterampilan: $("input[name='nilai_keterampilan["+id+"]']").val(),
            predikat_keterampilan: $("input[name='predikat_keterampilan["+id+"]']").val(),
            deskripsi_keterampilan: $("textarea[name='deskripsi_keterampilan["+id+"]']").val()
        };

        $.ajax({
            type: "POST",
            url: "{{ route('rapot.store') }}",
            data: formData,
            dataType: "JSON",
            success: function(response) {
                console.log("Success Response: ", response);
                Swal.fire('Sukses', 'Data berhasil disimpan!', 'success');
            },
            error: function(xhr) {
                console.log("Error Response: ", xhr);
                Swal.fire('Error', 'Terjadi kesalahan: ' + xhr.responseText, 'error');
            }
        });
    });
</script>
@endsection
