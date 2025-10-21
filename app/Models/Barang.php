<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $fillable = ['nama_barang', 'harga', 'stok', 'kategori', 'harga_grosir', 'gambar'];

    protected $casts = [
        'harga' => 'integer',
        'harga_grosir' => 'integer',
        'stok' => 'integer',
    ];

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }

    public function isNew()
    {
        return $this->transaksis()->where('status', 'selesai')->doesntExist();
    }
}
