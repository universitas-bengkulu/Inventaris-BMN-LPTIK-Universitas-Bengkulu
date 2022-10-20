@extends('layouts.layout')
@section('location','Dashboard')
@section('location2')
    <i class="fa fa-dashboard"></i>&nbsp;Transaksi Peminjaman
@endsection
@section('user-login','Operator')
@section('sidebar-menu')
    @include('operator/sidebar')
@endsection
@section('content')
    <div class="callout callout-info ">
        <h4>Perhatian!</h4>
        <p>
            Berikut adalah data transaksi peminjaman barang yang sudah tersedia, silahkan tambahkan jika ada transaksi peminjaman yang baru
            <br>
        </p>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-calendar"></i>&nbsp;Manajemen Data Transaksi Peminjaman</h3>
                    <div class="box-tools pull-right">
                        <a href="{{ route('barang.peminjaman.add') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i>&nbsp; Tambah Baru</a>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                            <i class="fa fa-success-circle"></i><strong>Berhasil :</strong> {{ $message }}
                        </div>
                    @endif
                    @if($message2 = Session::get('error'))
                        <div class="alert alert-danger alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>Gagal :</strong> {{ $message2 }}
                        </div>
                    @endif
                    <table class="table table-bordered table-hover" id="kelas">
                        <thead class="bg-primary">
                            <tr>
                                <th>No</th>
                                <th>Nama Peminjam</th>
                                <th>Tanggal Pinjam</th>
                                <th>Tanggal Kembali</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no=1;
                            @endphp
                            @forelse ($transaksis as $transaksi)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $transaksi->nama_peminjam }}</td>
                                <td>{{ $transaksi->tanggal_pinjam }}</td>
                                <td>{{ $transaksi->tanggal_kembali }} </td>
                                <td>
                                    <form action="{{ route('barang.peminjaman.update',[$transaksi->id]) }}" method="POST">
                                        {{ csrf_field() }} {{ method_field('PATCH') }}
                                        <select name="keterangan" id="" {{ $transaksi->keterangan == "sudah_dikembalikan" ? 'disabled' : '' }}>
                                            <option value="sudah_dikembalikan">Sudah Dikembalikan</option>
                                        </select>
                                        <br>
                                        <button class="btn btn-success btn-sm" {{ $transaksi->keterangan == "sudah_dikembalikan" ? 'disabled' : '' }}><i class="fa fa-spinner fa-spin"></i>&nbsp; Proses</button>
                                    </form>
                                </td>
                                <td>
                                    <a href="{{ route('pinjam_detail',[$transaksi->id]) }}" class="btn btn-primary btn-sm">Detail</a>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center"><label class="text-danger">Data Transaksi Keluar Tidak Tersedia</label></td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                 </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

@endpush
