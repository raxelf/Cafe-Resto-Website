<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function __construct(){
        $this->middleware('auth')->only(['list', 'create', 'edit']);
        $this->middleware('auth:api')->except(['list', 'create', 'edit']);
    }
    
    public function list()
    {
        return view('kategori.index');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 5);
        $searchQuery = $request->input('search', '');

        $query = Category::query();
        
        if (!empty($searchQuery)) {
            $query->where('nama_kategori', 'LIKE', '%' . $searchQuery . '%')
                  ->orWhere('deskripsi', 'LIKE', '%' . $searchQuery . '%');
        }

        $categories = $query->paginate($perPage);

        return response()->json([
            'data' => $categories->items(),
            'current_page' => $categories->currentPage(),
            'per_page' => $categories->perPage(),
            'total' => $categories->total(),
            'last_page' => $categories->lastPage(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kategori.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required',
            'deskripsi' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                422
            );
        }

        $input = $request->all();

        $category = Category::create($input);

        return response()->json([
            'success' => true,
            'data' => $category,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return response()->json([
            'data' => $category
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('kategori.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required',
            'deskripsi' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                422
            );
        }

        $input = $request->all();

        $category->update($input);

        return response()->json([
            'success' => true,
            'message' => 'success',
            'data' => $category
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'success'
        ]);
    }
}
