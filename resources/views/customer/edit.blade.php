@extends('layouts.app')

@section('title', 'Edit Customer')

@section('content')
    <h1>Edit Customer</h1>

    @if ($errors->any())
        <div style="color:red">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('customer.update', $customer->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Nama Customer:</label><br>
        <input type="text" name="nama_customer" value="{{ old('nama_customer', $customer->nama_customer) }}"><br><br>

        <label>Alamat:</label><br>
        <textarea name="alamat">{{ old('alamat', $customer->alamat) }}</textarea><br><br>

        <label>Tipe Pembeli:</label><br>
        <select name="tipe_pembeli">
            <option value="pembeli" {{ old('tipe_pembeli', $customer->tipe_pembeli) == 'pembeli' ? 'selected' : '' }}>Pembeli</option>
            <option value="bengkel" {{ old('tipe_pembeli', $customer->tipe_pembeli) == 'bengkel' ? 'selected' : '' }}>Bengkel</option>
            <option value="langganan" {{ old('tipe_pembeli', $customer->tipe_pembeli) == 'langganan' ? 'selected' : '' }}>Langganan</option>
        </select><br><br>

        <label>No HP:</label><br>
        <input type="text" name="no_hp" value="{{ old('no_hp', $customer->no_hp) }}"><br><br>

        <button type="submit">Update</button>
    </form>

    <a href="{{ route('customer.index') }}">Kembali</a>
@endsection