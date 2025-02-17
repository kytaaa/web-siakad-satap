@extends('template_backend.home')
@section('heading', 'Deskripsi Nilai')
@section('page')
  <li class="breadcrumb-item active">Deskripsi Nilai</li>
@endsection
@section('content')

<div class="col-md-12">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Deskripsi Nilai</h3>
        </div>

        @if(!isset($nilai) || !isset($guru))
            <div class="alert alert-danger">
                <strong>Error!</strong> Data nilai atau guru tidak ditemukan.
            </div>
        @else
        <form action="{{ route('nilai.store') }}" method="post">
            @csrf
            <input type="hidden" name="guru_id" value="{{ $guru->id }}">
            <input type="hidden" name="id" value="{{ $nilai->id ?? '' }}">

            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_guru">Nama Guru</label>
                            <input type="text" id="nama_guru" name="nama_guru" value="{{ $guru->nama_guru ?? '' }}" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label for="kode_mapel">Kode Mapel</label>
                            <input type="text" id="kode_mapel" name="kode_mapel" value="{{ $guru->kode ?? '' }}" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label for="deskripsi_a">Predikat A</label>
                            <textarea class="form-control" required name="deskripsi_a" id="deskripsi_a" rows="4">{{ old('deskripsi_a', $nilai->deskripsi_a) }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="deskripsi_c">Predikat C</label>
                            <textarea class="form-control" required name="deskripsi_c" id="deskripsi_c" rows="4">{{ old('deskripsi_c', $nilai->deskripsi_c) }}</textarea>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="mapel">Mata Pelajaran</label>
                            <input type="text" id="mapel" name="mapel" value="{{ $guru->mapel->nama_mapel ?? '' }}" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label for="kkm">KKM</label>
                            <input type="number" value="{{ old('kkm', $nilai->kkm) }}" id="kkm" name="kkm" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="deskripsi_b">Predikat B</label>
                            <textarea class="form-control" required name="deskripsi_b" id="deskripsi_b" rows="4">{{ old('deskripsi_b', $nilai->deskripsi_b) }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="deskripsi_d">Predikat D</label>
                            <textarea class="form-control" required name="deskripsi_d" id="deskripsi_d" rows="4">{{ old('deskripsi_d', $nilai->deskripsi_d) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <a href="#" name="kembali" class="btn btn-default" id="back"><i class='nav-icon fas fa-arrow-left'></i> &nbsp; Kembali</a> &nbsp;
                <button name="submit" class="btn btn-primary"><i class="nav-icon fas fa-save"></i> &nbsp; Simpan</button>
            </div>
        </form>
        @endif
    </div>
</div>

@endsection

@section('script')
<script type="text/javascript">
    $(document).ready(function() {
        $('#back').click(function() {
            window.location="{{ url('/') }}";
        });
    });
    $("#NilaiGuru").addClass("active");
    $("#liNilaiGuru").addClass("menu-open");
    $("#DesGuru").addClass("active");
</script>
@endsection
