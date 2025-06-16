<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
    </x-slot>

    <div class="py-12 px-6 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-lg shadow text-center">
            <h3 class="text-lg font-medium">Total Pengguna</h3>
            <p class="text-3xl mt-2 font-bold text-blue-500" id="total-users">0</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow text-center">
            <h3 class="text-lg font-medium">Total Produk</h3>
            <p class="text-3xl mt-2 font-bold text-green-500" id="total-products">0</p>
        </div>
    </div>

    <script>
        async function fetchDashboard() {
            const response = await fetch('/api/dashboard-stats');
            if (!response.ok) {
                console.error('API error:', response.status);
                return;
            }

            const data = await response.json();
            document.getElementById('total-users').textContent = data.total_users;
            document.getElementById('total-products').textContent = data.total_products;
        }

        fetchDashboard();
    </script>
</x-app-layout>

@dd(auth()->user());
