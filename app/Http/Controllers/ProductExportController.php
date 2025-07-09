<?php

namespace App\Http\Controllers;

use App\Exports\ProductsExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ProductExportController extends Controller
{
    public function export()
    {
        \Log::info('Export route accessed');
        return Excel::download(new ProductsExport, 'produk.xlsx');
    }
}
