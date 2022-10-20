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
        <div class="col-md-5">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <div class="box-tools pull-left">
                        <a href="{{ route('barang.peminjaman') }}" class="btn btn-danger btn-sm"><i class="fa fa-arrow-left"></i>&nbsp; Kembali</a>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered table-hover">
                                <thead class="bg-primary">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Barang</th>
                                        <th>Merk</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no=1;
                                    @endphp
                                    @forelse ($barangs as $barang)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $barang->nama_barang }}</td>
                                        <td>{{ $barang->merk }}</td>
                                        <td>
                                            <a href="{{ url('/transaksi_peminjaman/tambah_cart/'.$barang->id) }}" class="btn btn-primary btn-sm btn-flat" >Tambah Keranjang</a>
                                        </td>
                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="11" class="text-center"><label class="text-danger">Data Barang Tidak Tersedia</label></td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                 </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="box box-primary">
                <div class="box-header with-border">
                    Detail Peminjaman
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            @if ($message = Session::get('error'))
                                <div class="alert alert-danger alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                    <strong>Gagal :</strong> {{ $message }}
                                </div>
                            @endif
                        </div>

                        <div class="col-md-12">
                            <table class="table table-bordered table-hover" >
                                <thead>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Merk</th>
                                    <th>Jumlah</th>
                                    <th>Aksi</th>
                                </thead>
                                <tbody>
                                    @php
                                        $no=1;
                                        $total=0;
                                    @endphp
                                    @foreach ((array)$cart as $val=> $cart)
                                        @php
                                            $jumlah_2 = $cart['jumlah'];
                                        @endphp
                                        <tr>
                                            <td>{{ $cart['kode_barang'] }}</td>
                                            <td>{{ $cart['nama_barang'] }}</td>
                                            <td>{{ $cart['merk'] }}</td>
                                            <td>{{ $cart['jumlah'] }}</td>
                                            <td>
                                                <a href="{{ url('/transaksi_peminjaman/hapus_cart/'.$val) }}" class="btn btn-danger btn-sm btn-flat">Batalkan</a>
                                            </td>
                                        </tr>
                                        @php
                                            $total += $jumlah_2;
                                        @endphp
                                    @endforeach
                                    <tr>
                                        <th colspan="3" class="text-center">Total</th>
                                        <th>{{ $total }}</th>
                                        <th>
                                        </th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <form action="{{ route('pinjam') }}" method="POST">
                            {{ csrf_field() }} {{ method_field('POST') }}

                            <div class="form-group col-md-12">
                                <label for="exampleInputEmail1">Tanggal Pinjam</label>
                                <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" class="form-control">
                                <div>
                                    @if ($errors->has('tanggal_pinjam'))
                                        <small class="form-text text-danger">{{ $errors->first('tanggal_pinjam') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="exampleInputEmail1">Tanggal Kembali</label>
                                <input type="date" name="tanggal_kembali" id="tanggal_kembali" class="form-control">
                                <div>
                                    @if ($errors->has('tanggal_kembali'))
                                        <small class="form-text text-danger">{{ $errors->first('tanggal_kembali') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="exampleInputEmail1">Nama Peminjam</label>
                                <input type="text" name="nama_peminjam" id="nama_peminjam" class="form-control">
                                <div>
                                    @if ($errors->has('nama_peminjam'))
                                        <small class="form-text text-danger">{{ $errors->first('nama_peminjam') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-12">
                                <button type="submit" name="submit" class="btn btn-primary btn-sm"><i class="fa fa-check-circle"></i>&nbsp; Pinjam</button>
                            </div>
                        </form>
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

        $(document).on('change','#barang_id',function(){
            var barang_id = $(this).val();
            // alert(barang_id);
            var div = $(this).parent().parent();

            var op=" ";
            $('#mencari').show();
            $.ajax({
            type :'get',
            url: "{{ url('transaksi_barang_masuk/cari_barang') }}",
            data:{'barang_id':barang_id},
                success:function(data){
                    $('#barang').show();
                    $('#nama_barang').val(data.nama_barang);
                    $('#kategori').val(data.kategori);
                    $('#satuan').val(data.satuan);
                    $('#merk').val(data.merk);
                    $('#mencari').hide();
                },
                error:function(){

                }
            });
        })

        $('#tahun_anggaran').each(function() {
            var year = (new Date()).getFullYear();
            var current = year;
            year -= 20;
            for (var i = 0; i <= 20; i++) {
                if ((year+i) == current)
                    $(this).append('<option selected value="' + (year + i) + '">' + (year + i) + '</option>');
                else
                    $(this).append('<option value="' + (year + i) + '">' + (year + i) + '</option>');
            }
        });
    </script>
@endpush
