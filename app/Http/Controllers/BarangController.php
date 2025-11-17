<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::active(); // Only show non-deleted items

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('search')) {
            $query->where('nama_barang', 'like', '%' . $request->search . '%');
        }

        $barangs = $query->orderBy('created_at', 'desc')->get();

        // Get all available categories
        $allCategories = [
            'Sepeda Pacifik',
            'Sepeda Listrik',
            'Ban',
            'Sepeda Stroller',
            'Sparepart',
            'Lainnya'
        ];

        return view('barang.index', compact('barangs', 'allCategories'));
    }

    public function create()
    {
        return view('barang.create');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_barang' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('barangs', 'nama_barang')->where(function ($query) {
                        return $query->where('is_deleted', false);
                    })
                ],
                'harga' => 'required|integer|min:0',
                'harga_grosir' => 'required|integer|min:0',
                'stok' => 'required|integer|min:0',
                'kategori' => 'required|string|max:255',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ], [
                'nama_barang.required' => 'Nama barang harus diisi.',
                'nama_barang.unique' => 'Nama barang sudah ada, silakan gunakan nama lain.',
                'harga.required' => 'Harga retail harus diisi.',
                'harga_grosir.required' => 'Harga grosir harus diisi.',
            ]);

            $data = $request->only('nama_barang', 'harga', 'harga_grosir', 'stok', 'kategori');

            if ($request->hasFile('gambar')) {
                $imagePath = $request->file('gambar')->store('barang_images', 'public');
                $data['gambar'] = $imagePath;
            }

            Barang::create($data);

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Barang berhasil ditambahkan']);
            }

            return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false, 
                    'message' => $e->validator->errors()->first()
                ], 422);
            }
            throw $e;
        }
    }

    public function edit(Barang $barang)
    {
        return view('barang.edit', compact('barang'));
    }

    public function update(Request $request, Barang $barang)
    {
        try {
            $request->validate([
                'nama_barang' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('barangs', 'nama_barang')
                        ->ignore($barang->id)
                        ->where(function ($query) {
                            return $query->where('is_deleted', false);
                        })
                ],
                'harga' => 'required|integer|min:0',
                'harga_grosir' => 'required|integer|min:0',
                'stok' => 'required|integer|min:0',
                'kategori' => 'required|string|max:255',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ], [
                'nama_barang.required' => 'Nama barang harus diisi.',
                'nama_barang.unique' => 'Nama barang sudah ada, silakan gunakan nama lain.',
                'harga.required' => 'Harga retail harus diisi.',
                'harga_grosir.required' => 'Harga grosir harus diisi.',
            ]);

            $data = $request->only('nama_barang', 'harga', 'harga_grosir', 'stok', 'kategori');

            // Check if there are any changes
            $hasChanges = false;
            foreach ($data as $key => $value) {
                if ($barang->$key != $value) {
                    $hasChanges = true;
                    break;
                }
            }

            // Check if new image is uploaded
            if ($request->hasFile('gambar')) {
                $hasChanges = true;
                $imagePath = $request->file('gambar')->store('barang_images', 'public');
                $data['gambar'] = $imagePath;
            }

            // If no changes detected, return info message
            if (!$hasChanges) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => true, 
                        'message' => 'Tidak ada yang diperbarui', 
                        'no_changes' => true
                    ], 200);
                }

                return redirect()->route('barang.index')->with('info', 'Tidak ada yang diperbarui');
            }

            $barang->update($data);

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Barang berhasil diperbarui']);
            }

            return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false, 
                    'message' => $e->validator->errors()->first()
                ], 422);
            }
            throw $e;
        }
    }

    public function destroy(Request $request, Barang $barang)
    {
        // Soft delete: set is_deleted to true instead of hard delete
        $barang->update(['is_deleted' => true]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Barang berhasil dihapus']);
        }

        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus');
    }
}
