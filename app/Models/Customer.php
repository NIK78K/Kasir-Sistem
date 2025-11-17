<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['nama_customer', 'alamat', 'tipe_pembeli', 'no_hp'];

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }
}
