<?php

namespace App\Http\Controllers;

use App\Models\TransaksiModel;
use Illuminate\Support\Facades\DB;

class WelcomeController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Selamat Datang',
            'list'  => ['Home', 'Welcome']
        ];

        $activeMenu = 'dashboard';

        // Mengambil total penjualan
        $totalPenjualan = TransaksiModel::count();

        // Mengambil total detail transaksi
        $totalDetail = DB::table('t_penjualan_detail')->count();

        // Mengambil total penjualan per bulan
        $penjualanPerBulan = TransaksiModel::select(DB::raw('MONTH(penjualan_tanggal) as bulan'), DB::raw('COUNT(*) as total'))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan');

        return view('welcome', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
            'totalPenjualan' => $totalPenjualan,
            'totalDetail' => $totalDetail,
            'penjualanPerBulan' => $penjualanPerBulan
        ]);
    }
}
