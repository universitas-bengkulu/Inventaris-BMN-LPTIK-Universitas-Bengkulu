@extends('layouts.layout')
@section('location','Dashboard')
@section('location2')
    <i class="fa fa-dashboard"></i>&nbsp;Tambah Data Barang
@endsection
@section('user-login','Operator')
@section('sidebar-menu')
    @include('operator/sidebar')
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    Detail Peminjaman
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Nama Peminjam : {{ $data->nama_peminjam }}</h3>
                            <h3>Tanggal Pinjam : {{ $data->tanggal_pinjam }}</h3>
                            <h3>Tanggal Kembali : {{ $data->tanggal_kembali }}</h3>
                            <table class="table table-bordered table-hover" id="kelas">
                                <thead>
                                    <th>No</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Merk</th>
                                    <th>Jumlah</th>
                                </thead>
                                <tbody>
                                    @php
                                        $no=1;
                                    @endphp
                                    @foreach ($data->details()->get() as $cart)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $cart->barang->kode_barang }}</td>
                                            <td>{{ $cart->barang->nama_barang }}</td>
                                            <td>{{ $cart->barang->merk }}</td>
                                            <td>{{ $cart->jumlah }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                 </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready( function () {
            $('#kelas').DataTable();
        } );

    </script>
@endpush
