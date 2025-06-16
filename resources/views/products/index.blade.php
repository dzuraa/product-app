<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('Daftar Produk') }}
            </h2>
            <a href="{{ route('products.create') }}"
               class="inline-block px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700">
                + Tambah Produk
            </a>
        </div>
    </x-slot>

    <div class="py-12 px-4 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-4 p-4 text-green-700 bg-green-100 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white overflow-x-auto shadow sm:rounded-lg p-6">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mulai</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Selesai</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($products as $index => $product)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $products->firstItem() + $index }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $product->product_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">Rp{{ number_format($product->price, 2, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $product->stock }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $product->status }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $product->start_date?->format('d-m-Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $product->end_date?->format('d-m-Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap space-x-2">
                            <a href="{{ route('products.edit', $product->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Yakin ingin menghapus produk ini?')" class="text-red-600 hover:text-red-900">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
