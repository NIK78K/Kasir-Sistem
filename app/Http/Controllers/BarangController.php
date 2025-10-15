<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::query();

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('search')) {
            $query->where('nama_barang', 'like', '%' . $request->search . '%');
        }

        $barangs = $query->orderBy('created_at', 'desc')->get();

        return view('barang.index', compact('barangs'));
    }

    public function create()
    {
        return view('barang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255|unique:barangs,nama_barang',
            'harga' => 'required_without:harga_grosir|nullable|integer|min:0',
            'harga_grosir' => 'required_without:harga|nullable|integer|min:0',
            'stok' => 'required|integer|min:0',
            'kategori' => 'required|string|max:255',
            'diskon' => 'nullable|integer|min:0|max:100',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only('nama_barang', 'harga', 'harga_grosir', 'stok', 'kategori', 'diskon');

        // Convert empty strings to null for nullable fields
        $data['harga'] = $data['harga'] ?: null;
        $data['harga_grosir'] = $data['harga_grosir'] ?: null;

        if ($request->hasFile('gambar')) {
            $imagePath = $request->file('gambar')->store('barang_images', 'public');
            $data['gambar'] = $imagePath;
        }

        Barang::create($data);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan');
    }

    public function edit(Barang $barang)
    {
        return view('barang.edit', compact('barang'));
    }

    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255|unique:barangs,nama_barang,' . $barang->id,
            'harga' => 'required_without:harga_grosir|nullable|integer|min:0',
            'harga_grosir' => 'required_without:harga|nullable|integer|min:0',
            'stok' => 'required|integer|min:0',
            'kategori' => 'required|string|max:255',
            'diskon' => 'nullable|integer|min:0|max:100',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only('nama_barang', 'harga', 'harga_grosir', 'stok', 'kategori', 'diskon');

        // Convert empty strings to null for nullable fields
        $data['harga'] = $data['harga'] ?: null;
        $data['harga_grosir'] = $data['harga_grosir'] ?: null;

        if ($request->hasFile('gambar')) {
            $imagePath = $request->file('gambar')->store('barang_images', 'public');
            $data['gambar'] = $imagePath;
        }

        $barang->update($data);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui');
    }

    public function destroy(Barang $barang)
    {
        $barang->delete();

        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus');
    }
}
