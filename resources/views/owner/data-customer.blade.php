@extends('layouts.app')

@section('title', 'Data Customer (Owner)')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Data Customer (Hanya Lihat)</h1>

    <table class="min-w-full bg-white border border-gray-300 rounded">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">Nama Customer</th>
                <th class="py-2 px-4 border-b">Alamat</th>
                <th class="py-2 px-4 border-b">No HP</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customers as $customer)
                <tr>
                    <td class="py-2 px-4 border-b">{{ $customer->nama }}</td>
                    <td class="py-2 px-4 border-b">{{ $customer->alamat }}</td>
                    <td class="py-2 px-4 border-b">{{ $customer->no_hp }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
