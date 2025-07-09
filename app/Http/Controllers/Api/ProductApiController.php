<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/products",
     *     tags={"Products"},
     *     summary="List semua produk",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List produk berhasil diambil",
     *     )
     * )
     */
    public function index()
    {
        $products = Product::with('admin')->where('status', '!=', 'Delete')->latest()->paginate(10);
        return ProductResource::collection($products);
    }

    /**
     * @OA\Post(
     *     path="/api/products",
     *     tags={"Products"},
     *     summary="Membuat produk baru",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"product_name", "description", "price", "start_date", "end_date", "status", "stock"},
     *                 @OA\Property(property="product_name", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="price", type="number"),
     *                 @OA\Property(property="stock", type="integer"),
     *                 @OA\Property(property="start_date", type="string", format="date"),
     *                 @OA\Property(property="end_date", type="string", format="date"),
     *                 @OA\Property(property="status", type="string", enum={"Draft", "Active", "Inactive", "Delete"}),
     *                 @OA\Property(property="product_image", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="Produk berhasil dibuat"),
     *     @OA\Response(response=422, description="Validasi gagal")
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:Draft,Active,Inactive,Delete',
            'stock' => 'required|integer|min:0',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $validated;
        $data['admin_id'] = auth()->id();

        if ($request->hasFile('product_image')) {
            $data['product_image'] = $request->file('product_image')->store('products', 'public');
        }

        $product = Product::create($data);

        return response()->json(new ProductResource($product), 201);
    }

    /**
     * @OA\Get(
     *     path="/api/products/{id}",
     *     tags={"Products"},
     *     summary="Lihat detail produk",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Detail produk ditemukan"),
     *     @OA\Response(response=404, description="Produk tidak ditemukan")
     * )
     */
    public function show($id)
    {
        $product = Product::with('admin')->findOrFail($id);
        return new ProductResource($product);
    }

    /**
     * @OA\Put(
     *     path="/api/products/{id}",
     *     tags={"Products"},
     *     summary="Update produk",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="product_name", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="price", type="number"),
     *                 @OA\Property(property="stock", type="integer"),
     *                 @OA\Property(property="start_date", type="string", format="date"),
     *                 @OA\Property(property="end_date", type="string", format="date"),
     *                 @OA\Property(property="status", type="string", enum={"Draft", "Active", "Inactive", "Delete"}),
     *                 @OA\Property(property="product_image", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Produk berhasil diperbarui"),
     *     @OA\Response(response=404, description="Produk tidak ditemukan")
     * )
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // Validate all possible fields, but ensure 'nullable' is used for optional fields
        $validated = $request->validate([
            'product_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date', // Changed to after_or_equal for flexibility
            'status' => 'nullable|in:Draft,Active,Inactive,Delete',
            'stock' => 'nullable|integer|min:0',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Initialize an empty array to store data that will actually be updated
        $dataToUpdate = [];

        // Loop through the validated data. Only add to dataToUpdate if the field was actually present in the request
        // AND its value is not an empty string (to avoid overwriting with blanks).
        foreach ($validated as $key => $value) {
            // Check if the parameter was explicitly sent in the request AND
            // it's not an empty string (for string fields)
            // This ensures fields you don't send won't be modified, and
            // fields you send as empty strings (e.g., via Swagger) are ignored.
            if ($request->has($key) && ($value !== '' || !is_string($value))) {
                $dataToUpdate[$key] = $value;
            }
        }

        // Always ensure admin_id is set if an admin is authenticated
        $dataToUpdate['admin_id'] = auth()->id();

        // Handle product_image separately
        if ($request->hasFile('product_image')) {
            $dataToUpdate['product_image'] = $request->file('product_image')->store('products', 'public');
        }

        // Only fill with the filtered data.
        // Use `fill()` which respects the `$fillable` property, then `save()`.
        $product->fill($dataToUpdate)->save();

        // Return the updated product using ProductResource
        return response()->json(new ProductResource($product->fresh())); // Use fresh() to get the latest data from DB
    }

    /**
     * @OA\Delete(
     *     path="/api/products/{id}",
     *     tags={"Products"},
     *     summary="Hapus produk",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Produk berhasil dihapus"),
     *     @OA\Response(response=404, description="Produk tidak ditemukan")
     * )
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(['message' => 'Produk berhasil dihapus']);
    }
}
