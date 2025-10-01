@extends('layouts.app')

@section('title', 'List Return Barang')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <h1 class="text-3xl font-bold text-center mb-6 text-gray-800">🔄 List Return Barang</h1>

    @if($transaksis->count() > 0)
        <table class="min-w-full border-collapse mb-4">
            <thead>
                <tr class="bg-gray-100 text-gray-700">
                    <th class="border px-4 py-2">Nomor Transaksi</th>
                    <th class="border px-4 py-2">Nama Customer</th>
                    <th class="border px-4 py-2">Nama Barang</th>
                    <th class="border px-4 py-2">Jumlah</th>
                    <th class="border px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaksis as $transaksi)
                <tr>
                    <td class="border px-4 py-2">{{ str_pad($transaksi->id, 9, '0', STR_PAD_LEFT) }}</td>
                    <td class="border px-4 py-2">{{ $transaksi->customer->nama_customer }}</td>
                    <td class="border px-4 py-2">{{ $transaksi->barang->nama_barang }}</td>
                    <td class="border px-4 py-2 text-center">{{ $transaksi->jumlah }}</td>
                    <td class="border px-4 py-2 text-center">
                        <a href="{{ route('transaksi.barangReturn', ['id' => $transaksi->id]) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold">
                            Return
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $transaksis->links() }}
        </div>
    @else
        <p class="text-center text-gray-600">Tidak ada transaksi yang dapat direturn.</p>
    @endif
</div>
@endsection
