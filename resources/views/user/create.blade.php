@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-semibold mb-6 text-gray-800">Tambah User</h1>

        @if ($errors->any())
            <div class="mb-4 p-4 rounded bg-red-100 text-red-700">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('user.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block font-medium text-gray-700">Nama</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300 focus:outline-none"
                       placeholder="Masukkan nama user" required>
            </div>

            <div>
                <label class="block font-medium text-gray-700">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300 focus:outline-none"
                       placeholder="Masukkan email" required>
            </div>

            <div>
                <label class="block font-medium text-gray-700">Password</label>
                <input type="password" name="password"
                       class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300 focus:outline-none"
                       placeholder="Masukkan password" required>
            </div>

            <div>
                <label class="block font-medium text-gray-700">Konfirmasi Password</label>
                <input type="password" name="password_confirmation"
                       class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300 focus:outline-none"
                       placeholder="Konfirmasi password" required>
            </div>

            <div>
                <label class="block font-medium text-gray-700">Role</label>
                <select name="role"
                        class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300 focus:outline-none" required>
                    <option value="kasir" {{ old('role') == 'kasir' ? 'selected' : '' }}>Kasir</option>
                    <option value="owner" {{ old('role') == 'owner' ? 'selected' : '' }}>Owner</option>
                </select>
            </div>

            <div class="flex justify-between items-center">
                <a href="{{ route('user.index') }}"
                   class="px-4 py-2 bg-gray-500 text-white rounded-lg shadow hover:bg-gray-600 transition">
                    Batal
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
@endsection
