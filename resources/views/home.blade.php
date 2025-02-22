@extends('template_backend.home')
@section('heading', 'Dashboard')
@section('page')
  <li class="breadcrumb-item active">Dashboard</li>
@endsection
@section('content')
    <div class="col-md-12" id="load_content">
      <div class="card card-primary">
        <div class="card-body">
              <table class="table table-striped table-hover">
                <thead>
                  <tr>
                    <th>Jam Pelajaran</th>
                    <th>Mata Pelajaran</th>
                    <th>Kelas</th>
                    <th>Ruang Kelas</th>
                    <th>Ket.</th>
                  </tr>
                </thead>
                <tbody id="data-jadwal">
                    @php
                      $hari = date('w');
                      $jam = date('H:i');
                    @endphp
                    @if ( $jadwal->count() > 0 )
                      @if ($jam >= '09:30' && $jam <= '10:00')
                        <tr>
                          <td colspan='5' style='background:#fff;text-align:center;font-weight:bold;font-size:18px;'>Waktunya Istirahat!</td>
                        </tr>
                      @else
                        @foreach ($jadwal as $data)
                          <tr>
                            <td>{{ $data->jam_mulai.' - '.$data->jam_selesai }}</td>
                            <td>
                                <h5 class="card-title">{{ $data->mapel->nama_mapel }}</h5>
                                <p class="card-text"><small class="text-muted">{{ $data->guru->nama_guru }}</small></p>
                            </td>
                            <td>{{ $data->kelas->nama_kelas }}</td>
                            <td>{{ $data->ruang->nama_ruang }}</td>
                            <td>
                              @if ($data->absen($data->guru_id))
                                <div style="margin-left:20px;width:30px;height:30px;background:#{{ $data->absen($data->guru_id) }}"></div>
                              @elseif (date('H:i:s') >= '09:00:00')
                                <div style="margin-left:20px;width:30px;height:30px;background:#F00"></div>
                              @endif
                            </td>
                          </tr>
                        @endforeach
                      @endif
                    @elseif ($jam <= '07:00')
                      <tr>
                        <td colspan='5' style='background:#fff;text-align:center;font-weight:bold;font-size:18px;'>Jam Pelajaran Hari ini Akan Segera Dimulai!</td>
                      </tr>
                    @elseif (
                      ($hari >= 1 && $hari <= 4 && $jam >= '12:30') || 
                      ($hari >= 5 && $hari <= 6 && $jam >= '12:00')
                    )
                      <tr>
                        <td colspan='5' style='background:#fff;text-align:center;font-weight:bold;font-size:18px;'>Jam Pelajaran Hari ini Sudah Selesai!</td>
                      </tr>
                    @elseif ($hari == '0' || $hari == '7')
                      <tr>
                        <td colspan='5' style='background:#fff;text-align:center;font-weight:bold;font-size:18px;'>Sekolah Libur!</td>
                      </tr>
                    @elseif($hari == '1' && $jam >= '07:00' && $jam <= '07:30')
                      <tr>
                        <td colspan='5' style='background:#fff;text-align:center;font-weight:bold;font-size:18px;'>Waktunya Upacara Bendera!</td>
                      </tr>
                    @else
                      <tr>
                        <td colspan='5' style='background:#fff;text-align:center;font-weight:bold;font-size:18px;'>Tidak Ada Data Jadwal!</td>
                      </tr>
                    @endif
                </tbody>
              </table>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card card-warning" style="min-height: 385px;">
        <div class="card-header">
          <h3 class="card-title" style="color: white;">
            Pengumuman
          </h3>
        </div>
        <div class="card-body">
          <div class="tab-content p-0">
            {!! $pengumuman->isi !!}
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card card-info">
        <div class="card-header">
          <h3 class="card-title">
            Keterangan :
          </h3>
        </div>
        <div class="card-body">
          <div class="tab-content p-0">
            <table class="table" style="margin-top: -21px; margin-bottom: -10px;">
              @foreach ($kehadiran as $data)
                <tr>
                  <td>
                    <div style="width:30px;height:30px;background:#{{ $data->color }}"></div>
                  </td>
                  <td>:</td>
                  <td>{{ $data->ket }}</td>
                </tr>
              @endforeach
            </table>
          </div>
        </div>
      </div>
    </div>
@endsection
