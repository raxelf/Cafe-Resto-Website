<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Http\Controllers\Controller;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Xendit\Xendit;

class OrderController extends Controller
{
    public function __construct(){
        Xendit::setApiKey(config('services.xendit.secret_key'));
        $this->middleware('auth')->only('list');
        $this->middleware('auth:api')->except('list', 'showOrderDetails', 'placeOrder', 'webhook');
    }

    public function list(){
        $orders = Order::with('product')->get();
        $products = Product::all();

        return view('pesanan.index', compact('orders', 'products'));
    }

    public function showOrderDetails($sessionId)
    {
        $cartItems = CartItem::where('session_id', $sessionId)->with('product')->get();

        return view('order.details', ['cartItems' => $cartItems]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 5);
        $searchQuery = $request->input('search', '');

        $query = Order::query()->orderByRaw("created_at DESC, 
        CASE 
            WHEN status = 'Baru' THEN 1
            WHEN status = 'Dikemas' THEN 2
            WHEN status = 'Dikirim' THEN 3
            WHEN status = 'Diterima' THEN 4
            WHEN status = 'Selesai' THEN 5
        END");

        if (!empty($searchQuery)) {
            $query->where('invoice', 'LIKE', '%' . $searchQuery . '%')
            ->orWhere('grand_total', 'LIKE', '%' . $searchQuery . '%');
        }

        $orders = $query->paginate($perPage);

        return response()->json([
            'data' => $orders->items(),
            'current_page' => $orders->currentPage(),
            'per_page' => $orders->perPage(),
            'total' => $orders->total(),
            'last_page' => $orders->lastPage(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }


    private function getCartItems(Request $request)
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

        return [
            'totalPrice' => $totalPrice,
            'orderId' => session()->getId(),
            'productList' => $productList,
        ];
    }
    
    public function placeOrder(Request $request)
    {
        $nama = $request->input('nama');
        $nowhatsapp = $request->input('nowhatsapp');
        $selectedAddress = $request->input('selectedAddress');
        $detailAlamat = $request->input('detailalamat');
        $paymentMethod = $request->input('paymentMethod');

        $invoice = 'OB-' . $this->generateRandomString();;

        $order = new Order();
        $order->invoice = $invoice;
        $order->nama = $nama;
        $order->nowhatsapp = $nowhatsapp;
        $order->alamat = $selectedAddress;
        $order->detail_alamat = $detailAlamat;
        $order->grand_total = 0;
        $order->payment_method = $paymentMethod;
        $order->status_pembayaran = 'UNPAID';
        $order->status = 'Baru';
        
        $sessionId = $request->session()->getId();
        $cartItems = CartItem::where('session_id', $sessionId)->with('product')->get();

        $grandTotal = 0;
        $order->save();

        $descriptions = [];
        foreach ($cartItems as $cartItem) {
            $grandTotal += $cartItem->quantity * ($cartItem->product->harga - $cartItem->product->diskon);
            $orderDetail = new OrderDetail();
            $orderDetail->id_order = $order->id;
            $orderDetail->id_barang = $cartItem->product_id;
            $orderDetail->jumlah = $cartItem->quantity;
            $orderDetail->total = $cartItem->quantity * ($cartItem->product->harga - $cartItem->product->diskon);
            $orderDetail->save();
            
            $descriptions[] = $cartItem->product->nama_barang;
        }
        $totalDescriptions = count($descriptions);

        if ($totalDescriptions > 2) {
            $firstTwoDescriptions = array_slice($descriptions, 0, 2);
            $descriptions = $firstTwoDescriptions;
            $descriptions[] = '+' . ($totalDescriptions - 2) . ' barang lainnya';
        }
        
        $description = implode(', ', $descriptions);
        
        $order->grand_total = $grandTotal;
        $order->save();

        $this->clearCart($request);

        if($paymentMethod !== "cash"){
            $params = [
                'external_id' => $invoice,
                'amount' => $grandTotal,
                'description' => $description,
                'payment_methods' => [$paymentMethod],
                'success_redirect_url' => route('thankyou', ['invoice' => $invoice]),
                'customer' => [
                    'mobile_number' => $nowhatsapp,
                ],
                'customer_notification_preference' => [
                    'invoice_created' => [
                        'whatsapp',
                    ],
                    'invoice_reminder' => [
                        'whatsapp',
                    ],
                    'invoice_paid' => [
                        'whatsapp',
                    ],
                    'invoice_expired' => [
                        'whatsapp',
                    ]
                ],
            ];
    
            $fees = [];
    
            if ($paymentMethod === 'BRI' || $paymentMethod === 'BNI') {
                $feeAmount = 5000;
                $fee = [
                    'type' => 'ADMIN',
                    'value' => $feeAmount,
                ];
                $fees[] = $fee;
                $params['amount'] += $feeAmount;
            } else if ($paymentMethod === 'DANA' || $paymentMethod === 'SHOPEEPAY') {
                $feeAmount = $grandTotal * 0.04;
            
                $fee = [
                    'type' => 'TAX',
                    'value' => $feeAmount,
                ];
                $fees[] = $fee;
                $params['amount'] += $feeAmount;
            } else if ($paymentMethod === 'QRIS') {
                $feeAmount = $grandTotal * 0.02;
            
                $fee = [
                    'type' => 'TAX',
                    'value' => $feeAmount,
                ];
                $fees[] = $fee;
                $params['amount'] += $feeAmount;
            }
    
            $params['fees'] = $fees;
            $createInvoice = \Xendit\Invoice::create($params);
    
            $order->checkout_link = $createInvoice['invoice_url'];
            $order->save();
        }

        return response()->json([
            'message' => 'Order placed successfully', 
            'invoice' => $order->invoice,
            'checkout_link' => $order->checkout_link,
        ]);
    }

    public function webhook(Request $request)
    {
        $getInvoice = \Xendit\Invoice::retrieve($request->id);
        $order = Order::where('invoice', $request->external_id)->firstorFail();

        if ($order->status_pembayaran == 'settled' || $order->status_pembayaran == 'paid') {
            return response()->json([
                'data' => 'Payment has been already processed',
            ]);
        }

        if (strtolower($getInvoice['status']) == 'expired') {
            $order->delete();
            
            return response()->json([
                'data' => 'Payment has expired and has been deleted from the database',
            ]);
        }

        $order->status_pembayaran = strtolower($getInvoice['status']);
        $order->save();

        return response()->json([
            'data' => 'Success',
        ]);
    }

    private function generateRandomString($length = 8)
    {
        return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    }

    public function clearCart(Request $request)
    {
        $sessionId = $request->session()->getId();

        CartItem::where('session_id', $sessionId)->delete();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $Order)
    {
        $orderWithDetails = $Order->load('orderDetails.product');

        return response()->json([
            'data' => $orderWithDetails
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $Order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $Order)
    {
        
    }

    public function ubah_status(Request $request, Order $order)
    {
        $status = $request->status;

        $order->update([
            'status' => $status,
        ]);

        if ($order->payment_method === 'cash') {
            $order->update([
                'status_pembayaran' => ($status === 'Selesai') ? 'paid' : 'UNPAID',
            ]);
        }

        return response()->json([
            'message' => 'success',
            'data' => $order,
        ]);
    }

    public function baru(Request $request) {
        $perPage = $request->input('per_page', 5);
        $searchQuery = $request->input('search', '');

        $query = Order::query()->where('status', 'Baru')->orderBy('created_at', 'desc');

        if (!empty($searchQuery)) {
            $query->where('invoice', 'LIKE', '%' . $searchQuery . '%')
                ->orWhere('grand_total', 'LIKE', '%' . $searchQuery . '%');
        }

        $orders = $query->paginate($perPage);

        return response()->json([
            'data' => $orders->items(),
            'current_page' => $orders->currentPage(),
            'per_page' => $orders->perPage(),
            'total' => $orders->total(),
            'last_page' => $orders->lastPage(),
        ]);
    }
    public function dikemas(Request $request) {
        $perPage = $request->input('per_page', 5);
        $searchQuery = $request->input('search', '');

        $query = Order::query()->where('status', 'Dikemas')->orderBy('created_at', 'desc');

        if (!empty($searchQuery)) {
            $query->where('invoice', 'LIKE', '%' . $searchQuery . '%')
                ->orWhere('grand_total', 'LIKE', '%' . $searchQuery . '%');
        }

        $orders = $query->paginate($perPage);

        return response()->json([
            'data' => $orders->items(),
            'current_page' => $orders->currentPage(),
            'per_page' => $orders->perPage(),
            'total' => $orders->total(),
            'last_page' => $orders->lastPage(),
        ]);
    }
    public function diantar(Request $request) {
        $perPage = $request->input('per_page', 5);
        $searchQuery = $request->input('search', '');

        $query = Order::query()->where('status', 'Diantar')->orderBy('created_at', 'desc');

        if (!empty($searchQuery)) {
            $query->where('invoice', 'LIKE', '%' . $searchQuery . '%')
                ->orWhere('grand_total', 'LIKE', '%' . $searchQuery . '%');
        }

        $orders = $query->paginate($perPage);

        return response()->json([
            'data' => $orders->items(),
            'current_page' => $orders->currentPage(),
            'per_page' => $orders->perPage(),
            'total' => $orders->total(),
            'last_page' => $orders->lastPage(),
        ]);
    }

    public function selesai(Request $request) {
        $perPage = $request->input('per_page', 5);
        $searchQuery = $request->input('search', '');

        $query = Order::query()->where('status', 'Selesai')->orderBy('created_at', 'desc');

        if (!empty($searchQuery)) {
            $query->where('invoice', 'LIKE', '%' . $searchQuery . '%')
                ->orWhere('grand_total', 'LIKE', '%' . $searchQuery . '%');
        }

        $orders = $query->paginate($perPage);

        return response()->json([
            'data' => $orders->items(),
            'current_page' => $orders->currentPage(),
            'per_page' => $orders->perPage(),
            'total' => $orders->total(),
            'last_page' => $orders->lastPage(),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $Order)
    {
        $Order->delete();

        return response()->json([
            'message' => 'success'
        ]);
    }
}
