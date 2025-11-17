<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Barang;
use App\Models\Customer;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'customer_id',
        'barang_id',
        'jumlah',
        'harga_barang',
        'parent_transaksi_id',
        'total_harga',
        'uang_dibayar',
        'kembalian',
        'tanggal_pembelian',
        'tipe_pembayaran',
        'status',
        'alasan_return',
    ];

    protected $casts = [
    'tanggal_pembelian' => 'datetime',
];


    public function customer()
    {
        return $this->belongsTo(Customer::class)->withTrashed();
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class)->withoutGlobalScopes();
    }

    public function parentTransaksi()
    {
        return $this->belongsTo(Transaksi::class, 'parent_transaksi_id');
    }

    public function childTransaksis()
    {
        return $this->hasMany(Transaksi::class, 'parent_transaksi_id');
    }
}
