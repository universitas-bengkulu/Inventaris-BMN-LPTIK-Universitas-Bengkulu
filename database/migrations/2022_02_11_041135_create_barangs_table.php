<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ruang_id');
            $table->string('kode_barang');
            $table->string('nama_barang');
            $table->string('merk');
            $table->enum('jenisBarang',['elektronik','nonelektronik']);
            $table->enum('kategori',['aset','barang_habis_pakai']);
            $table->integer('jumlah_barang');
            $table->enum('satuan',['Unit','Pcs','Lembar']);
            $table->string('tahun_anggaran');
            $table->string('asalPerolehan');
            $table->enum('sumber_dana',['apbn','pnpb']);
            $table->enum('kondisi',['baik','rusak','sedang_dipinjam','hilang']);
            $table->bigInteger('harga');
            $table->text('catatan');
            $table->date('tanggal_masuk');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('barangs');
    }
}
