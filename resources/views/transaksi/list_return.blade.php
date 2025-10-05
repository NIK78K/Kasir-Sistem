@extends('layouts.app')

@section('title', auth()->user()->role == 'owner' ? 'Laporan Return Barang' : 'List Return Barang')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <h1 class="text-3xl font-bold text-center mb-6 text-gray-800">{{ auth()->user()->role == 'owner' ? '📋 Laporan Return Barang' : '🔄 List Return Barang' }}</h1>

    @if($transaksis->count() > 0)
        <table class="min-w-full border-collapse mb-4">
            <thead>
                <tr class="bg-gray-100 text-gray-700">
                    <th class="border px-4 py-2">Nomor Transaksi</th>
                    <th class="border px-4 py-2">Nama Customer</th>
                    <th class="border px-4 py-2">Nama Barang</th>
                    <th class="border px-4 py-2">Jumlah</th>
                    @if(auth()->user()->role == 'owner')
                        <th class="border px-4 py-2">Status</th>
                        <th class="border px-4 py-2">Tanggal Return</th>
                    @else
                        <th class="border px-4 py-2">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($transaksis as $transaksi)
                <tr>
                    <td class="border px-4 py-2">{{ str_pad($transaksi->id, 9, '0', STR_PAD_LEFT) }}</td>
                    <td class="border px-4 py-2">{{ $transaksi->customer->nama_customer }}</td>
                    <td class="border px-4 py-2">{{ $transaksi->barang->nama_barang }}</td>
                    <td class="border px-4 py-2 text-center">{{ $transaksi->jumlah }}</td>
                    @if(auth()->user()->role == 'owner')
                        <td class="border px-4 py-2 text-center">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                {{ $transaksi->status == 'return' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ ucfirst(str_replace('_', ' ', $transaksi->status)) }}
                            </span>
                        </td>
                        <td class="border px-4 py-2">{{ $transaksi->updated_at->format('d-m-Y H:i') }}</td>
                    @else
                        <td class="border px-4 py-2 text-center">
                            <a href="{{ route('transaksi.barangReturn', ['id' => $transaksi->id]) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold">
                                Return
                            </a>
                        </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $transaksis->links() }}
        </div>
    @else
        <p class="text-center text-gray-600">{{ auth()->user()->role == 'owner' ? 'Belum ada data return barang.' : 'Tidak ada transaksi yang dapat direturn.' }}</p>
    @endif
</div>
@endsection
