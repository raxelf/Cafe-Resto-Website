<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(){
        $foodProducts = DB::table('products')
            ->join('categories', 'products.id_kategori', '=', 'categories.id')
            ->where('categories.nama_kategori', 'like', '%food%')
            ->select('products.*')
            ->take(4)
            ->get();

        $drinkProducts = DB::table('products')
            ->join('categories', 'products.id_kategori', '=', 'categories.id')
            ->where('categories.nama_kategori', 'like', '%drink%')
            ->select('products.*')
            ->take(4)
            ->get();

        $allProducts = Product::all()->shuffle();
        $additionalProducts = $allProducts->take(2);

        return view('home.index', compact('foodProducts', 'drinkProducts', 'additionalProducts'));
    }

    public function getRandomProduct()
    {
        $randomProduct = Product::all()->shuffle()->first();

        return response()->json(['randomProduct' => $randomProduct]);
    }

    public function getCategoryId(Request $request)
    {
        $namaKategori = $request->input('nama_kategori');

        $category = Category::where('nama_kategori', $namaKategori)->first();

        if ($category) {
            $categoryId = $category->id;

            return response()->json(['categoryId' => $categoryId]);
        } else {
            return response()->json(['error' => 'Category not found'], 404);
        }
    }

    public function menu()
    {
        $categories = Category::all(); 

        $products = Product::all();

        $defaultCategoryId = $products->isEmpty() ? null : $products->first()->id;

        return view('home.menu', compact('categories', 'products', 'defaultCategoryId'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);

        $cartItem = CartItem::where('session_id', session()->getId())->where('product_id', $productId)->first();

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            CartItem::create([
                'session_id' => session()->getId(),
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
        }

        return response()->json([
            'success' => true,
            'orderId' => session()->getId(),
            'message' => 'Product added to cart successfully'
        ]);
    }

    public function addQuantity($itemId)
    {
        $cartItem = CartItem::find($itemId);

        if (!$cartItem) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        $cartItem->quantity += 1;
        $cartItem->save();

        return response()->json(['message' => 'Quantity added successfully', 'cart_item' => $cartItem]);
    }

    public function decreaseQuantity($itemId)
    {
        $cartItem = CartItem::find($itemId);

        if (!$cartItem) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        $cartItem->quantity = max(1, $cartItem->quantity - 1);
        $cartItem->save();

        return response()->json(['message' => 'Quantity decreased successfully', 'cart_item' => $cartItem]);
    }

    public function destroyCart($id)
    {
        try {
            $cartItem = CartItem::findOrFail($id);
            $cartItem->delete();

            return response()->json(['message' => 'Item successfully removed from the cart'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error removing item from the cart'], 500);
        }
    }
    
    public function thankYouPage($invoice)
    {
        $order = Order::where('invoice', $invoice)->first();

        if ($order) {
            $orderDetails = OrderDetail::where('id_order', $order->id)->with('product')->get();

            return view('order.thankyou', ['order' => $order, 'orderDetails' => $orderDetails]);
        } else {
            abort(404);
        }
    }

    public function getCartItems(Request $request)
    {
        $sessionId = $request->session()->getId();

        $cartItems = CartItem::where('session_id', $sessionId)->with('product')->get();

        $totalPrice = 0;
        $productList = [];

        foreach ($cartItems as $cartItem) {
            $totalPrice += $cartItem->quantity * ($cartItem->product->harga - $cartItem->product->diskon);

            $productList[] = [
                'name' => $cartItem->product->nama_barang,
                'quantity' => $cartItem->quantity,
                'subtotal' => $cartItem->quantity * ($cartItem->product->harga - $cartItem->product->diskon),
            ];
        }

        return response()->json([
            'totalPrice' => $totalPrice,
            'orderId' => session()->getId(),
            'productList' => $productList,
        ]);
    }
}
