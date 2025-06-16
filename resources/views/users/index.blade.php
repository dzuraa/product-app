@php
    $isSuperAdmin = auth()->user()->isSuperAdmin();
@endphp

<x-app-layout>
    <x-slot name="header">
        @if($isSuperAdmin)
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('Daftar Pengguna') }}
            </h2>
            <a href="{{ route('users.create') }}"
               class="inline-block px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700">
                + Tambah User
            </a>
        </div>
        @endif
    </x-slot>

    <div class="py-12 px-4 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-4 p-4 text-green-700 bg-green-100 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white overflow-x-auto shadow sm:rounded-lg p-6">
            <table class="min-w-full divide-y divide-gray-200">
                <!-- Tambahkan kolom Aksi di tabel -->
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe User</th>
                        @if($isSuperAdmin)
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($users as $index => $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $users->firstItem() + $index }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->user_type }}</td>
                        @if ($isSuperAdmin)
                        <td class="px-6 py-4 whitespace-nowrap space-x-2">
                                <a href="{{ route('users.edit', $user->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Yakin ingin menghapus user ini?')" class="text-red-600 hover:text-red-900">Hapus</button>
                                </form>
                            </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
