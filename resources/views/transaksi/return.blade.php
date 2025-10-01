@extends('layouts.app')

@section('title', 'Return Barang')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    {{-- Judul --}}
    <h1 class="text-3xl font-bold text-center mb-6 text-gray-800">🔄 Return Barang</h1>

    {{-- Alert sukses --}}
    @if(session('success'))
        <div class="mb-4 p-4 rounded-lg bg-green-100 text-green-700 border border-green-300">
            ✅ {{ session('success') }}
        </div>
    @endif

    {{-- Error Messages --}}
    @if($errors->any())
        <div class="mb-4 p-4 rounded-lg bg-red-100 text-red-700 border border-red-300">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Data Transaksi --}}
    <div class="bg-white shadow-lg rounded-2xl mb-6 overflow-hidden border border-gray-200 p-6">
        <h2 class="text-xl font-semibold mb-4">Data Barang Transaksi</h2>
        <p><strong>Nomor Transaksi:</strong> {{ str_pad($transaksi->id, 9, '0', STR_PAD_LEFT) }}</p>
        <p><strong>Nama Customer:</strong> {{ $transaksi->customer->nama_customer }}</p>
        <p><strong>Tipe Customer:</strong> {{ ucfirst($transaksi->customer->tipe_pembeli) }}</p>
    </div>

    {{-- Form Return --}}
    <form action="{{ route('transaksi.return', ['id' => $transaksi->id]) }}" method="POST" class="bg-white shadow-lg rounded-2xl border border-gray-200 p-6">
        @csrf
        <h2 class="text-xl font-semibold mb-4">Daftar Belanja</h2>
        <table class="min-w-full border-collapse mb-4">
            <thead>
                <tr class="bg-gray-100 text-gray-700">
                    <th class="border px-4 py-2">Barang</th>
                    <th class="border px-4 py-2">Jumlah</th>
                    <th class="border px-4 py-2">Return</th>
                    <th class="border px-4 py-2">Jumlah akan direturn</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border px-4 py-2">{{ $transaksi->barang->nama_barang }}</td>
                    <td class="border px-4 py-2 text-center">{{ $transaksi->jumlah }}</td>
                    <td class="border px-4 py-2 text-center">
                        <input type="checkbox" name="items[0][return]" value="1" id="return_0" class="form-checkbox" onchange="toggleJumlahReturn(0)">
                    </td>
                    <td class="border px-4 py-2 text-center">
                        <input type="hidden" name="items[0][transaksi_id]" value="{{ $transaksi->id }}">
                        <input type="number" name="items[0][jumlah_return]" id="jumlah_return_0" value="0" min="0" max="{{ $transaksi->jumlah }}" class="border rounded w-20 text-center" disabled>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="mb-4">
            <label for="alasan_return" class="block font-medium text-gray-700 mb-1">Alasan Pengembalian Barang</label>
            <textarea name="alasan_return" id="alasan_return" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500"></textarea>
        </div>

        <button type="submit" class="px-5 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700 transition">
            Proses Return
        </button>
    </form>
</div>

<script>
    function toggleJumlahReturn(index) {
        const checkbox = document.getElementById('return_' + index);
        const jumlahInput = document.getElementById('jumlah_return_' + index);
        jumlahInput.disabled = !checkbox.checked;
        if (!checkbox.checked) {
            jumlahInput.value = 0;
        } else {
            jumlahInput.value = 1;
        }
    }
</script>
@endsection
