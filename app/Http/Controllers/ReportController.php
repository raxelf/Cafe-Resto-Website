<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function __construct(){
        $this->middleware('auth')->only(['index']);
        $this->middleware('auth:api')->except(['index']);
    }

    public function index(){
        return view('report.index');
    }

    public function get_reports(Request $request){
        $report = DB::table('order_details')
            ->join('products', 'products.id', '=', 'order_details.id_barang')
            ->select(DB::raw('
                count(*) as jumlah_dibeli, 
                nama_barang, 
                harga, 
                SUM(total) as pendapatan, 
                SUM(jumlah) as total_qty'))
            ->whereRaw("date(order_details.created_at) >= '$request->dari'")
            ->whereRaw("date(order_details.created_at) <= '$request->sampai'")
            ->groupBy('id_barang', 'nama_barang', 'harga')
            ->orderBy('jumlah_dibeli', 'desc')
            ->paginate(5);
    
        return response()->json([
            'data' => $report->items(),
            'current_page' => $report->currentPage(),
            'per_page' => $report->perPage(),
            'total' => $report->total(),
            'last_page' => $report->lastPage(),
        ]);
    }
}
