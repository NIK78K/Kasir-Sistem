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
        @if(auth()->user()->role == 'owner')
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Laporan Penjualan</h3>
                <a href="{{ route('owner.laporanPenjualanExport') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Export to Excel
                </a>
            </div>
        @else
            <h3 class="mb-4 text-lg font-semibold">Transaksi yang ingin dibatalkan</h3>
        @endif
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase border-b border-gray-300">ID</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase border-b border-gray-300">Nama Pembeli</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase border-b border-gray-300">Nama Barang</th>
                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase border-b border-gray-300">Jumlah</th>
                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase border-b border-gray-300">Harga Barang</th>

                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase border-b border-gray-300">Total Harga</th>
                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase border-b border-gray-300">Tanggal Pembelian</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase border-b border-gray-300">Tipe Pembayaran</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase border-b border-gray-300">Alamat Pengantaran</th>
                        @if(auth()->user()->role != 'owner')
                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase border-b border-gray-300">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($transaksis as $transaksi)
                        <tr class="hover:bg-gray-100">
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700 border-b border-gray-300">{{ $transaksi->id }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700 border-b border-gray-300">{{ $transaksi->customer->nama_customer }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700 border-b border-gray-300">{{ $transaksi->barang->nama_barang }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-center text-sm text-gray-700 border-b border-gray-300">{{ $transaksi->jumlah }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-right text-sm text-gray-700 border-b border-gray-300">Rp {{ number_format($transaksi->harga_barang, 0, ',', '.') }}</td>

                            <td class="px-4 py-2 whitespace-nowrap text-right text-sm text-gray-700 border-b border-gray-300">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-center text-sm text-gray-700 border-b border-gray-300">{{ $transaksi->tanggal_pembelian->format('d-m-Y') }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700 border-b border-gray-300">{{ ucfirst($transaksi->tipe_pembayaran) }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700 border-b border-gray-300">{{ $transaksi->alamat_pengantaran }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-center text-sm border-b border-gray-300 space-x-2">
                                @if(auth()->user()->role != 'owner')
                                    <a href="{{ route('transaksi.listBatal', ['id' => $transaksi->id]) }}" class="text-blue-600 hover:underline">Batalkan</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $transaksis->links() }}
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
