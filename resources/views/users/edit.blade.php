<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit User</h2>
    </x-slot>

    <div class="py-12 max-w-4xl mx-auto">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <form method="POST" action="{{ route('users.update', $user->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                </div>

                <div class="mb-4">
                    <label for="user_type" class="block text-sm font-medium text-gray-700">Tipe User</label>
                    <select name="user_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        <option value="Superadmin" {{ $user->user_type === 'Superadmin' ? 'selected' : '' }}>Superadmin</option>
                        <option value="Admin" {{ $user->user_type === 'Admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-2">
                    <a href="{{ route('users.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Batal</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
