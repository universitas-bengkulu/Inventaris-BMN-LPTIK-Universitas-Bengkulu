<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;
    protected $fillable = [
        'tanggal_pinjam','tanggal_kembali','nama_peminjam'
    ];

    public function details(){
        return $this->hasMany(PeminjamanDetail::class);
    }
}
