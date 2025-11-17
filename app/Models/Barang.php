<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Barang extends Model
{
    use HasFactory;

    protected $fillable = ['nama_barang', 'harga', 'stok', 'kategori', 'harga_grosir', 'gambar', 'is_deleted'];

    protected $casts = [
        'harga' => 'integer',
        'harga_grosir' => 'integer',
        'stok' => 'integer',
        'is_deleted' => 'boolean',
    ];

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }

    public function isNew()
    {
        return $this->transaksis()->where('status', 'selesai')->doesntExist();
    }

    public function scopeActive($query)
    {
        return $query->where('is_deleted', false);
    }

    /**
     * Return WebP version of the stored image if it exists (same basename, .webp extension).
     */
    public function webpPath(): ?string
    {
        if (!$this->gambar) return null;
        $candidate = preg_replace('/\.(jpe?g|png)$/i', '.webp', $this->gambar);
        if ($candidate && Storage::disk('public')->exists($candidate)) {
            return $candidate;
        }
        return null;
    }
}
