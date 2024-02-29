<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function __construct(){
        $this->middleware('auth')->only(['list', 'create', 'edit']);
        $this->middleware('auth:api')->except(['list', 'create', 'edit']);
    }

    public function list()
    {
        return view('barang.index');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 5);
        $searchQuery = $request->input('search', '');

        $query = Product::query()->with('category');

        if (!empty($searchQuery)) {
            $query->where('nama_barang', 'LIKE', '%' . $searchQuery . '%')
                  ->orWhere('sku', 'LIKE', '%' . $searchQuery . '%');
        }

        $products = $query->paginate($perPage);

        return response()->json([
            'data' => $products->items(),
            'current_page' => $products->currentPage(),
            'per_page' => $products->perPage(),
            'total' => $products->total(),
            'last_page' => $products->lastPage(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::with('category')->get();
        $categories = Category::all();
        return view('barang.create', compact('products', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_kategori' => 'required',
            'nama_barang' => 'required',
            'deskripsi' => 'required',
            'gambar' => 'required|image|mimes:jpg,png,jpeg,webp',
            'harga' => 'required',
            'diskon' => 'required',
            'sku' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                422
            );
        }

        $input = $request->all();

        if ($request->has('gambar')){
            $gambar = $request->file('gambar');
            $nama_gambar = 'OppaBox-' . time() . rand(1, 9) . '.' . $gambar->getClientOriginalExtension();
            $gambar->move('uploads/Products/', $nama_gambar);
            $input['gambar'] = $nama_gambar;
        }

        $Product = Product::create($input);

        return response()->json([
            'success' => true,
            'data' => $Product,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load('category');

        return response()->json([
            'data' => $product
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $Product)
    {
        $products = Product::with('category')->get();
        $categories = Category::all();
        return view('barang.edit', compact('products', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $Product)
    {
        $validator = Validator::make($request->all(), [
            'id_kategori' => 'required',
            'nama_barang' => 'required',
            'deskripsi' => 'required',
            'gambar' => 'image|mimes:jpg,png,jpeg,webp',
            'harga' => 'required',
            'diskon' => 'required',
            'sku' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                422
            );
        }

        $input = $request->all();

        if($request->has('gambar')){
            File::delete('uploads/Products/' . $Product->gambar);
            $gambar = $request->file('gambar');
            $nama_gambar = 'OppaBox-' . time() . rand(1, 9) . '.' . $gambar->getClientOriginalExtension();
            $gambar->move('uploads/Products/', $nama_gambar);
            $input['gambar'] = $nama_gambar;
        } else{
            unset($input['gambar']);
        }

        $Product->update($input);

        return response()->json([
            'success' => true,
            'message' => 'success',
            'data' => $Product
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $Product)
    {
        File::delete('uploads/Products/' . $Product->gambar);
        $Product->delete();

        return response()->json([
            'success' => true,
            'message' => 'success'
        ]);
    }
}
