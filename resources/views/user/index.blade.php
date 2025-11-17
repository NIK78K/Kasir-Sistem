@extends('layouts.app')

@section('title', 'Data User')

@section('content')
<div class="max-w-6xl mx-auto p-6">
        {{-- Banner Selamat Datang --}}
    <div class="mb-8 rounded-2xl p-6 shadow-lg bg-gray-700">
        <h1 class="text-2xl md:text-3xl font-bold text-white flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
            </svg>
            Data User
        </h1>
    </div>
    <h2 class="sr-only">Daftar User</h2>

    {{-- Tombol Tambah --}}
    <div class="mb-6 flex justify-end">
        <button type="button"
                class="px-5 py-2.5 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg shadow-md hover:from-green-700 hover:to-green-800 transition flex items-center gap-2 font-semibold"
                @click="$dispatch('open-modal', 'create-user-modal')">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah User
        </button>
    </div>

    {{-- Alert (Hidden - using global popup) --}}
    @if (session('success'))
        <div id="success-alert" class="hidden">{{ session('success') }}</div>
    @endif

    @if (session('info'))
        <div id="info-alert" class="hidden">{{ session('info') }}</div>
    @endif

    @if (session('error'))
        <div id="error-alert" class="hidden">{{ session('error') }}</div>
    @endif

    {{-- Table for Desktop --}}
    <div class="hidden md:block overflow-x-auto bg-white shadow-lg rounded-xl border border-gray-200">
        <table class="min-w-full text-sm text-gray-700">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-300">
                <tr>
                    <th class="py-4 px-4 text-left font-semibold text-gray-800">Nama</th>
                    <th class="py-4 px-4 text-left font-semibold text-gray-800">Email</th>
                    <th class="py-4 px-4 text-left font-semibold text-gray-800">Role</th>
                    <th class="py-4 px-4 text-center font-semibold text-gray-800">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($users as $user)
                    <tr id="user-{{ $user->id }}" class="hover:bg-gray-50 transition">
                        <td class="py-4 px-4 font-medium text-gray-900">{{ $user->name }}</td>
                        <td class="py-4 px-4">{{ $user->email }}</td>
                        <td class="py-4 px-4">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-700">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="py-4 px-4 text-center">
                            <div class="flex justify-center gap-2">
                                <button type="button"
                                        @click="$dispatch('open-modal', 'edit-user-modal-{{ $user->id }}')"
                                        class="btn-edit-user px-4 py-2 bg-blue-600 text-white rounded-lg shadow-sm hover:bg-blue-700 transition font-semibold text-xs"
                                        data-id="{{ $user->id }}">
                                    Edit
                                </button>
                                <button type="button"
                                    class="btn-delete-user px-4 py-2 bg-red-600 text-white rounded-lg shadow-sm hover:bg-red-700 transition font-semibold text-xs"
                                    data-id="{{ $user->id }}"
                                    data-nama="{{ $user->name }}">
                                    Hapus
                                </button>
                                <form id="delete-form-{{ $user->id }}" action="{{ route('user.destroy', $user->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach

                {{-- Jika kosong --}}
                @if ($users->isEmpty())
                    <tr>
                        <td colspan="4" class="py-8 text-center text-gray-500">
                            Belum ada data user.
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    {{-- Cards for Mobile --}}
    <div class="block md:hidden space-y-4">
        @foreach ($users as $user)
            <div class="bg-white shadow-lg rounded-xl border border-gray-200 p-4">
                <div class="flex justify-between items-start mb-3">
                    <p class="text-lg font-bold text-gray-900">{{ $user->name }}</p>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-700">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
                <div class="space-y-2 text-sm text-gray-600">
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                </div>
                <div class="mt-4 flex gap-2">
                    <button type="button"
                            @click="$dispatch('open-modal', 'edit-user-modal-{{ $user->id }}')"
                            class="btn-edit-user flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg shadow-sm hover:bg-blue-700 transition font-semibold text-center text-sm"
                            data-id="{{ $user->id }}">
                        Edit
                    </button>
                    <button type="button"
                        class="btn-delete-user flex-1 px-4 py-2 bg-red-600 text-white rounded-lg shadow-sm hover:bg-red-700 transition font-semibold text-sm"
                        data-id="{{ $user->id }}"
                        data-nama="{{ $user->name }}">
                        Hapus
                    </button>
                    <form id="delete-form-{{ $user->id }}" action="{{ route('user.destroy', $user->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        @endforeach

        {{-- Jika kosong --}}
        @if ($users->isEmpty())
            <div class="bg-white shadow-lg rounded-xl border border-gray-200 p-8 text-center text-gray-500">
                Belum ada data user.
            </div>
        @endif
    </div>
</div>

{{-- Modal Create User --}}
<x-modal name="create-user-modal" maxWidth="2xl"
    x-on:close-modal.window="if ($event.detail === 'create-user-modal') { $refs.createUserForm?.reset(); }"
    x-on:open-modal.window="if ($event.detail === 'create-user-modal') { $refs.createUserForm?.reset(); }">
    <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Tambah User Baru</h2>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form x-ref="createUserForm" id="create-user-form" action="{{ route('user.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="create-name" class="block font-semibold mb-1">Nama:</label>
                <input id="create-name" type="text" name="name"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Masukkan nama user" required />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="create-email" class="block font-semibold mb-1">Email:</label>
                    <input id="create-email" type="email" name="email"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Masukkan email user" required />
                </div>

                <div>
                    <label for="create-role" class="block font-semibold mb-1">Role:</label>
                    <select id="create-role" name="role"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="kasir">Kasir</option>
                        <option value="owner">Owner</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <button type="button" 
                    @click="$refs.createUserForm.reset(); $dispatch('close-modal', 'create-user-modal')"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</x-modal>

{{-- Modal Edit User --}}
@foreach ($users as $user)
<x-modal name="edit-user-modal-{{ $user->id }}" maxWidth="2xl">
    <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Edit User</h2>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="edit-user-form-{{ $user->id }}" action="{{ route('user.update', $user->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="edit-name-{{ $user->id }}" class="block font-semibold mb-1">Nama:</label>
                <input id="edit-name-{{ $user->id }}" type="text" name="name" value="{{ $user->name }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="edit-email-{{ $user->id }}" class="block font-semibold mb-1">Email:</label>
                    <input id="edit-email-{{ $user->id }}" type="email" name="email" value="{{ $user->email }}"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required />
                </div>

                <div>
                    <label for="edit-role-{{ $user->id }}" class="block font-semibold mb-1">Role:</label>
                    <select id="edit-role-{{ $user->id }}" name="role"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="kasir" {{ $user->role == 'kasir' ? 'selected' : '' }}>Kasir</option>
                        <option value="owner" {{ $user->role == 'owner' ? 'selected' : '' }}>Owner</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <button type="button" 
                    @click="$dispatch('close-modal', 'edit-user-modal-{{ $user->id }}')"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                    Update
                </button>
            </div>
        </form>
    </div>
</x-modal>
@endforeach

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add event listeners to delete buttons
        document.querySelectorAll('.btn-delete-user').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const namaUser = this.getAttribute('data-nama');
                confirmDeleteUser(id, namaUser);
            });
        });



        // Create user form submission - Submit form secara normal (tanpa SPA)
        // Form akan di-submit ke server dan refresh browser setelah success
        // Event listener dihapus agar form submit secara default

        // Handle Edit User Form Submit dengan AJAX validasi
        let editFormsValidated = {};
        
        document.addEventListener('submit', function(e) {
            if (e.target.id && e.target.id.startsWith('edit-user-form-')) {
                const formId = e.target.id;
                
                console.log('Edit user form submitted:', formId); // Debug log
                
                if (editFormsValidated[formId]) {
                    // Sudah divalidasi, biarkan submit normal
                    console.log('Form already validated, submitting normally'); // Debug log
                    delete editFormsValidated[formId]; // Reset flag
                    return true;
                }
                
                e.preventDefault();
                console.log('Form prevented, checking via AJAX'); // Debug log
                
                const form = e.target;
                const formData = new FormData(form);
                
                // Show loading
                Swal.fire({
                    title: 'Memeriksa...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    console.log('Response status:', response.status); // Debug log
                    return response.json();
                })
                .then(data => {
                    Swal.close();
                    
                    console.log('Response data:', data); // Debug log
                    console.log('Has no_changes flag:', data.no_changes); // Debug log
                    
                    if (data.success) {
                        // Close modal first
                        const userId = formId.replace('edit-user-form-', '');
                        window.dispatchEvent(new CustomEvent('close-modal', { detail: 'edit-user-modal-' + userId }));
                        
                        // Check if there are no changes
                        if (data.no_changes) {
                            console.log('No changes detected, showing info popup'); // Debug log
                            
                            Swal.fire({
                                title: 'Info',
                                text: data.message,
                                icon: 'info',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK'
                            });
                            return;
                        }
                        
                        // Show success popup and reload
                        console.log('Changes detected, showing success popup'); // Debug log
                        Swal.fire({
                            title: 'Berhasil!',
                            text: data.message,
                            icon: 'success',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            // Reload the page to show updated data
                            window.location.reload();
                        });
                    } else {
                        // Tampilkan popup error
                        Swal.fire({
                            title: 'Gagal!',
                            text: data.message || 'Terjadi kesalahan saat memperbarui user',
                            icon: 'error',
                            confirmButtonColor: '#d33',
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(error => {
                    Swal.close();
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat memperbarui user',
                        icon: 'error',
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'OK'
                    });
                });
            }
        });

    });

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex', 'items-center', 'justify-center');
        }
    }

    function confirmDeleteUser(id, namaUser) {
        Swal.fire({
            title: 'Apakah Anda yakin menghapus user?',
            text: `User "${namaUser}" akan dihapus secara permanen!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Menghapus...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });

                // AJAX delete request
                fetch(`{{ url('user') }}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    Swal.close();

                    if (data.success) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: data.message,
                            icon: 'success',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            // Reload the current page content via AJAX
                            window.dispatchEvent(new CustomEvent('load-page', { detail: { url: window.location.href, routeName: 'user.index' } }));
                        });
                    } else {
                        Swal.fire({
                            title: 'Gagal!',
                            text: data.message || 'Terjadi kesalahan saat menghapus user',
                            icon: 'error',
                            confirmButtonColor: '#d33',
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(error => {
                    Swal.close();
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat menghapus user',
                        icon: 'error',
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'OK'
                    });
                });
            }
        });
    }
</script>
@endsection
