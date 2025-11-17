@extends('layouts.app')

@section('title', 'Return Barang')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    {{-- Judul --}}
    <h1 class="text-3xl font-bold text-center mb-6 text-gray-800">🔄 Return Barang</h1>
    <h2 class="sr-only">Form Pengembalian Barang</h2>

    {{-- Alert sukses (Hidden - using global popup) --}}
    @if(session('success'))
        <div class="hidden">{{ session('success') }}</div>
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
        <h2 class="text-xl font-semibold mb-4">Data Transaksi</h2>
        <p><strong>Nomor Order:</strong> {{ $transaksi->order_id }}</p>
        <p><strong>Nama Customer:</strong> {{ $transaksi->customer->nama_customer }}</p>
        <p><strong>Tipe Customer:</strong> {{ ucfirst($transaksi->customer->tipe_pembeli) }}</p>
        <p><strong>Tanggal Pembelian:</strong> {{ $transaksi->tanggal_pembelian->format('d-m-Y H:i') }}</p>
    </div>

    {{-- Form Return --}}
    <form id="return_form" action="{{ route('transaksi.return', ['id' => $transaksi->id]) }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-lg rounded-2xl border border-gray-200 p-6">
        @csrf
        <h2 class="text-xl font-semibold mb-4">Daftar Belanja</h2>
        <div class="overflow-x-auto">
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
                    @foreach($orderTransaksis as $index => $item)
                        <tr>
                            <td class="border px-4 py-2">{{ $item->barang->nama_barang }}</td>
                            <td class="border px-4 py-2 text-center">{{ $item->jumlah }}</td>
                            <td class="border px-4 py-2 text-center">
                                <input type="checkbox" name="items[{{ $index }}][return]" value="1" id="return_{{ $index }}" class="form-checkbox" onchange="toggleJumlahReturn({{ $index }})">
                            </td>
                            <td class="border px-4 py-2 text-center">
                                <input type="hidden" name="items[{{ $index }}][transaksi_id]" value="{{ $item->id }}">
                                <input type="number" name="items[{{ $index }}][jumlah_return]" id="jumlah_return_{{ $index }}" value="0" min="0" max="{{ $item->jumlah }}" class="border rounded w-20 text-center" disabled>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mb-4">
            <label for="alasan_return" class="block font-medium text-gray-700 mb-1">Alasan Pengembalian Barang</label>
            <textarea name="alasan_return" id="alasan_return" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500"></textarea>
        </div>



        <div class="flex gap-3">
            <a href="{{ route('transaksi.listReturnable') }}" class="px-5 py-2 bg-gray-600 text-white rounded-lg shadow hover:bg-gray-700 transition">
                Kembali
            </a>
            <button id="submit_return_btn" type="submit" class="px-5 py-2 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg shadow hover:bg-gray-700 transition">
                Proses Return
            </button>
        </div>
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
            // default to 1 when enabling, but allow user edit
            if (!jumlahInput.value || parseInt(jumlahInput.value) === 0) jumlahInput.value = 1;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // If user types jumlah_return > 0, auto-check the corresponding checkbox
        document.querySelectorAll('input[name$="[jumlah_return]"]').forEach(inp => {
            inp.addEventListener('input', function() {
                const match = this.name.match(/items\[(\d+)\]/);
                if (!match) return;
                const idx = match[1];
                const cb = document.getElementById('return_' + idx);
                const val = parseInt(this.value) || 0;
                if (val > 0) {
                    if (cb) cb.checked = true;
                }
            });
        });

        // Form submit validation: show warning if no valid return quantity provided
        const form = document.getElementById('return_form');
        form.addEventListener('submit', function(e) {
            const checkboxes = document.querySelectorAll('input[name^="items"][type="checkbox"]');
            const jumlahInputs = document.querySelectorAll('input[name$="[jumlah_return]"]');

            let valid = false;

            // Check jumlah inputs first
            jumlahInputs.forEach(inp => {
                const val = parseInt(inp.value) || 0;
                if (val > 0) valid = true;
            });

            // Also consider checkboxes only valid if their jumlah_return > 0
            checkboxes.forEach(cb => {
                if (cb.checked) {
                    const idx = cb.id.split('_')[1];
                    const jumlah = document.getElementById('jumlah_return_' + idx);
                    if (jumlah && (parseInt(jumlah.value) || 0) > 0) valid = true;
                }
            });

            if (!valid) {
                e.preventDefault();
                const msg = 'Masukkan jumlah barang yang akan direturn';
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: msg,
                        confirmButtonColor: '#3085d6'
                    });
                } else {
                    alert(msg);
                }
            }
        });
    });
</script>
@endsection
