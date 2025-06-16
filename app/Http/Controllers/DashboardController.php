<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalProducts = Product::where('status', '!=', 'Delete')->count();

        return view('dashboard', compact('totalUsers', 'totalProducts'));
    }

    public function apiStats()
    {
        return response()->json([
            'total_users' => User::count(),
            'total_products' => Product::where('status', '!=', 'Delete')->count()
        ]);
    }
}
