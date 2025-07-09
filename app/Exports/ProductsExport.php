<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Product::all();
    }

    public function headings(): array
    {
        return ['ID', 'Nama Produk', 'Harga', 'Stok', 'Status', 'Tanggal Mulai', 'Tanggal Selesai'];
    }

    public function map($product): array
    {
        return [
            $product->id,
            $product->product_name,
            $product->price !== null ? number_format($product->price, 0, ',', '.') : '0',
            $product->stock,
            $product->status,
            $product->start_date ? \Carbon\Carbon::parse($product->start_date)->format('d-m-Y') : '-',
            $product->end_date ? \Carbon\Carbon::parse($product->end_date)->format('d-m-Y') : '-',
        ];
    }
}
