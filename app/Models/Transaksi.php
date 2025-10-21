<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'barang_id',
        'jumlah',
        'harga_barang',

        'total_harga',
        'uang_dibayar',
        'kembalian',
        'tanggal_pembelian',
        'tipe_pembayaran',
        'alamat_pengantaran',
        'status',
    ];

    protected $casts = [
    'tanggal_pembelian' => 'date',
];


    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
