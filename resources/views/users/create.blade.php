<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Tambah Pengguna') }}
        </h2>
    </x-slot>

    <div class="py-12 px-4 sm:px-6 lg:px-8 max-w-3xl mx-auto">
        <div class="bg-white p-6 shadow rounded-lg">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        required>
                    @error('name')
                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        required>
                    @error('email')
                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="password"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        required>
                    @error('password')
                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="user_type" class="block text-sm font-medium text-gray-700">Tipe Pengguna</label>
                    <select name="user_type" id="user_type"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        required>
                        <option value="">-- Pilih --</option>
                        <option value="Superadmin" {{ old('user_type') == 'Superadmin' ? 'selected' : '' }}>Superadmin</option>
                        <option value="Admin" {{ old('user_type') == 'Admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('user_type')
                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('users.index') }}"
                       class="mr-3 px-4 py-2 bg-gray-300 text-sm rounded hover:bg-gray-400">Batal</a>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
