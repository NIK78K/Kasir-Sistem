@extends('layouts.app')

@section('title', 'Transaksi Batal')

@section('content')

    @if(isset($transaksi))
        <div class="max-w-md mx-auto border border-black rounded-xl p-6 space-y-6">
            <a href="{{ route('transaksi.listBatal') }}" class="font-bold text-black hover:underline flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                <span>Detail Transaksi</span>
            </a>
            <div class="space-y-2">
                <p><strong>Nomor Transaksi</strong> : {{ str_pad($transaksi->id, 9, '0', STR_PAD_LEFT) }}</p>
                <p><strong>Nama Customer</strong> : {{ $transaksi->customer->nama_customer }}</p>
                <p><strong>Tipe Customer</strong> : {{ ucfirst($transaksi->customer->tipe_pembeli) ?? 'N/A' }}</p>
            </div>
            <div>
                <strong>Daftar Belanja</strong>
                <div class="mt-2 space-y-2">
                    <div class="flex justify-between font-semibold">
                        <span>{{ $transaksi->barang->nama_barang }}</span>
                        <span>{{ $transaksi->jumlah }}</span>
                        <span>Rp {{ number_format($transaksi->harga_barang, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            <form method="POST" action="{{ route('transaksi.batal') }}" class="space-y-4" onsubmit="handleCancelSubmit(event)">
                @csrf
                <input type="hidden" name="transaksi_id" value="{{ $transaksi->id }}">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="confirm_batal" value="1" class="form-checkbox h-5 w-5 text-black">
                    <span>Konfirmasi pembatalan transaksi</span>
                </label>
                <button type="submit" class="w-full py-2 border border-black rounded-full hover:bg-black hover:text-white transition font-semibold">
                    Batalkan Transaksi
                </button>
            </form>
        </div>
    @elseif(isset($transaksis))
        <div class="max-w-6xl mx-auto p-6">
            {{-- Banner Selamat Datang --}}
            <div class="mb-8 rounded-2xl p-6 shadow-lg bg-gray-700">
                <h1 class="text-2xl md:text-3xl font-bold text-white flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    @if(auth()->user()->role == 'owner')
                        Laporan Penjualan
                    @else
                        Transaksi Batal
                    @endif
                </h1>
            </div>

            {{-- Tombol Export untuk Owner --}}
            @if(auth()->user()->role == 'owner')
                <div class="mb-6 flex justify-end">
                    <a href="{{ route('owner.laporanPenjualanExport') }}"
                        class="px-5 py-2.5 bg-green-600 text-white rounded-lg shadow-md hover:bg-green-700 transition flex items-center gap-2 font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Export to Excel
                    </a>
                </div>
            @endif

            {{-- Alert --}}
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-800 rounded-lg shadow" id="success-alert">
                    ✅ {{ session('success') }}
                </div>
            @endif

            {{-- Table for Desktop --}}
            <div class="hidden md:block overflow-x-auto bg-white shadow-lg rounded-xl border border-gray-200">
                <table class="min-w-full text-sm text-gray-700">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-300">
                        <tr>
                            <th class="py-4 px-4 text-left font-semibold text-gray-800">ID</th>
                            <th class="py-4 px-4 text-left font-semibold text-gray-800">Nama Pembeli</th>
                            <th class="py-4 px-4 text-left font-semibold text-gray-800">Tipe Pembeli</th>
                            <th class="py-4 px-4 text-left font-semibold text-gray-800">Nama Barang</th>
                            <th class="py-4 px-4 text-center font-semibold text-gray-800">Jumlah</th>
                            <th class="py-4 px-4 text-right font-semibold text-gray-800">Harga Barang</th>
                            <th class="py-4 px-4 text-right font-semibold text-gray-800">Total Harga</th>
                            <th class="py-4 px-4 text-center font-semibold text-gray-800">Tanggal Pembelian</th>
                            <th class="py-4 px-4 text-left font-semibold text-gray-800">Tipe Pembayaran</th>
                            <th class="py-4 px-4 text-left font-semibold text-gray-800">Alamat Pengantaran</th>
                            @if(auth()->user()->role != 'owner')
                                <th class="py-4 px-4 text-center font-semibold text-gray-800">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($transaksis as $transaksi)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-4 px-4">{{ $transaksi->id }}</td>
                                <td class="py-4 px-4 font-medium text-gray-900">{{ $transaksi->customer->nama_customer }}</td>
                                <td class="py-4 px-4">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ ($transaksi->customer->tipe_pembeli ?? '') == 'grosir' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                        {{ ucfirst($transaksi->customer->tipe_pembeli ?? '-') }}
                                    </span>
                                </td>
                                <td class="py-4 px-4">{{ $transaksi->barang->nama_barang }}</td>
                                <td class="py-4 px-4 text-center">{{ $transaksi->jumlah }}</td>
                                <td class="py-4 px-4 text-right">Rp {{ number_format($transaksi->harga_barang, 0, ',', '.') }}</td>
                                <td class="py-4 px-4 text-right font-bold text-blue-600">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                                <td class="py-4 px-4 text-center">{{ $transaksi->tanggal_pembelian->format('d-m-Y') }}</td>
                                <td class="py-4 px-4">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full
                                        {{ $transaksi->tipe_pembayaran == 'cash' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                                        {{ ucfirst($transaksi->tipe_pembayaran) }}
                                    </span>
                                </td>
                                <td class="py-4 px-4">{{ $transaksi->alamat_pengantaran }}</td>
                                @if(auth()->user()->role != 'owner')
                                    <td class="py-4 px-4 text-center">
                                        <a href="{{ route('transaksi.listBatal', ['id' => $transaksi->id]) }}"
                                            class="px-4 py-2 bg-red-600 text-white rounded-lg shadow-sm hover:bg-red-700 transition font-semibold text-xs">
                                            Batalkan
                                        </a>
                                    </td>
                                @endif
                            </tr>
                        @endforeach

                        {{-- Jika kosong --}}
                        @if ($transaksis->isEmpty())
                            <tr>
                                <td colspan="{{ auth()->user()->role != 'owner' ? '10' : '9' }}" class="py-8 text-center text-gray-500">
                                    Belum ada data transaksi.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            {{-- Cards for Mobile --}}
            <div class="block md:hidden space-y-4">
                @foreach ($transaksis as $transaksi)
                    <div class="bg-white shadow-lg rounded-xl border border-gray-200 p-4">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="text-lg font-bold text-gray-900">{{ $transaksi->customer->nama_customer }}</h3>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $transaksi->tipe_pembayaran == 'cash' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                                {{ ucfirst($transaksi->tipe_pembayaran) }}
                            </span>
                        </div>
                        <div class="space-y-2 text-sm text-gray-600">
                            <p><strong>ID:</strong> {{ $transaksi->id }}</p>
                            <p><strong>Tipe Pembeli:</strong>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ ($transaksi->customer->tipe_pembeli ?? '') == 'grosir' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                    {{ ucfirst($transaksi->customer->tipe_pembeli ?? '-') }}
                                </span>
                            </p>
                            <p><strong>Barang:</strong> {{ $transaksi->barang->nama_barang }}</p>
                            <p><strong>Jumlah:</strong> {{ $transaksi->jumlah }}</p>
                            <p><strong>Harga Barang:</strong> Rp {{ number_format($transaksi->harga_barang, 0, ',', '.') }}</p>
                            <p><strong>Total Harga:</strong> <span class="font-bold text-blue-600">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span></p>
                            <p><strong>Tanggal:</strong> {{ $transaksi->tanggal_pembelian->format('d-m-Y') }}</p>
                            <p><strong>Alamat:</strong> {{ $transaksi->alamat_pengantaran }}</p>
                        </div>
                        @if(auth()->user()->role != 'owner')
                            <div class="mt-4">
                                <a href="{{ route('transaksi.listBatal', ['id' => $transaksi->id]) }}"
                                    class="w-full px-4 py-2 bg-red-600 text-white rounded-lg shadow-sm hover:bg-red-700 transition font-semibold text-center text-sm">
                                    Batalkan Transaksi
                                </a>
                            </div>
                        @endif
                    </div>
                @endforeach

                {{-- Jika kosong --}}
                @if ($transaksis->isEmpty())
                    <div class="bg-white shadow-lg rounded-xl border border-gray-200 p-8 text-center text-gray-500">
                        Belum ada data transaksi.
                    </div>
                @endif
            </div>

            {{-- Pagination --}}
            @if($transaksis->hasPages())
                <div class="mt-6">
                    {{ $transaksis->links() }}
                </div>
            @endif
        </div>
    @endif

@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            Swal.fire({
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        @endif
    });

    function confirmCancelTransaction() {
        return Swal.fire({
            title: 'Apakah Anda yakin?',
            text: 'Transaksi ini akan dibatalkan dan stok barang akan dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Batalkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            return result.isConfirmed;
        });
    }

    function handleCancelSubmit(event) {
        event.preventDefault(); // Prevent default form submission

        const checkbox = document.querySelector('input[name="confirm_batal"]');
        if (!checkbox.checked) {
            Swal.fire({
                title: 'Peringatan!',
                text: 'Silakan centang konfirmasi pembatalan transaksi terlebih dahulu.',
                icon: 'warning',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
            return false;
        }

        confirmCancelTransaction().then((confirmed) => {
            if (confirmed) {
                event.target.submit(); // Submit the form if confirmed
            }
        });
    }
</script>
