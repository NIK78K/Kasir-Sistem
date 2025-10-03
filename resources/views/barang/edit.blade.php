@extends('layouts.app')

@section('title', 'Edit Barang')

@section('content')
    <h1>Edit Barang</h1>

    @if ($errors->any())
        <div style="color:red">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('barang.update', $barang->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Nama Barang:</label><br>
        <input type="text" name="nama_barang" value="{{ old('nama_barang', $barang->nama_barang) }}"><br><br>

        <label>Harga:</label><br>
        <input type="number" name="harga" value="{{ old('harga', $barang->harga) }}"><br><br>

        <label>Stok:</label><br>
        <input type="number" name="stok" value="{{ old('stok', $barang->stok) }}"><br><br>

        <button type="submit">Update</button>
    </form>

    <a href="{{ route('barang.index') }}">Kembali</a>
@endsection