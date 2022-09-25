@extends('layouts.layout')
@section('location','Dashboard')
@section('location2')
    <i class="fa fa-dashboard"></i>&nbsp;Edit Data Ruangan
@endsection
@section('user-login','Operator')
@section('sidebar-menu')
    @include('operator/sidebar')
@endsection
@section('content')
    <div class="callout callout-info text-center">
        <h4>Perhatian!</h4>
        <p>
            Silahkan ubah data ruangan pada form di bawah ini, harap untuk teliti agar tidak terjadi kesalahan dalam proses pengisian data !!
            <br>
        </p>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <div class="box-tools pull-left">
                        <a href="{{ route('ruangan') }}" class="btn btn-danger btn-sm"><i class="fa fa-arrow-left"></i>&nbsp; Kembali</a>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <form action="{{ route('ruangan.update',[$ruangan->id]) }}" method="POST">
                        {{ csrf_field() }} {{ method_field('PATCH') }}
                        <div class="form-group col-md-6">
                            <label for="exampleInputEmail1">Nama ruangan</label>
                            <input type="text" name="nama_ruangan" value="{{ $ruangan->nama_ruangan }}" class="form-control">
                            <div>
                                @if ($errors->has('nama_ruangan'))
                                    <small class="form-text text-danger">{{ $errors->first('nama_ruangan') }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="exampleInputEmail1">Peanggung Jawab Ruangan</label>
                            <select name="penanggung_jawab_id" id="penanggung_jawab_id" class="form-control">
                                <option>-- pilih penanggung jawab --</option>
                                @foreach ($pjs as $pj)
                                    <option value="{{ $pj->id }}" @if ($pj->id == $ruangan->penanggung_jawab_id)
                                        selected
                                    @endif>{{ $pj->nama_lengkap }}</option>
                                @endforeach
                            </select>
                            <div>
                                @if ($errors->has('penanggung_jawab_id'))
                                    <small class="form-text text-danger">{{ $errors->first('penanggung_jawab_id') }}</small>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12 text-center">
                            <button type="reset" name="reset" class="btn btn-warning btn-sm"><i class="fa fa-refresh"></i>&nbsp; Ulangi</button>
                            <button type="submit" name="submit" class="btn btn-primary btn-sm"><i class="fa fa-check-circle"></i>&nbsp; Simpan Perubahan</button>
                        </div>
                    </form>
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

        $(document).on('change','#anggota_id',function(){
            var anggota_id = $(this).val();
            var div = $(this).parent().parent();
            var op=" ";
            $.ajax({
            type :'get',
            url: "{{ url('operator/simpanan_wajib/cari_bulan') }}",
            data:{'anggota_id':anggota_id},
                success:function(data){
                    op+='<option value="0" selected disabled>-- pilih bulan --</option>';
                    for(var i=0; i<data.length;i++){
                        // alert(data[i].id);
                        // alert(data['jenis_publikasi'][i].anggota_id);
                        op+='<option value="'+data[i].bulan_transaksi+'">'+data[i].bulan_transaksi+'</option>';
                    }
                    div.find('#bulan').html(" ");
                    div.find('#bulan').append(op);
                },
                    error:function(){
                }
            });
        })
    </script>
@endpush
