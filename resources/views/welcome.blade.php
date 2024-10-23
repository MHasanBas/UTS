@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Dashboard Statistik</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-shopping-cart"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Penjualan</span>
                                    <span class="info-box-number">{{ $totalPenjualan }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-list"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Detail Transaksi</span>
                                    <span class="info-box-number">{{ $totalDetail }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian untuk menampilkan grafik batang sederhana -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5 class="text-center mb-4">Statistik Penjualan Bulanan</h5>
                            <div class="chart">
                                @foreach($penjualanPerBulan as $bulan => $total)
                                    <div class="chart-bar">
                                        <div class="bar" style="height: {{ $total * 10 }}px;" title="{{ $total }} penjualan"></div>
                                        <span class="label">{{ $bulan }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <style>
                        .chart {
                            display: flex;
                            align-items: flex-end;
                            justify-content: space-around;
                            height: 300px;
                            border-left: 2px solid #ccc;
                            border-bottom: 2px solid #ccc;
                            padding: 10px;
                            background-color: #f9f9f9; /* Background color for the chart area */
                            border-radius: 8px; /* Rounded corners */
                            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Shadow effect */
                        }

                        .chart-bar {
                            text-align: center;
                            margin: 0 5px; /* Add margin for better spacing */
                        }

                        .bar {
                            width: 30px;
                            background-color: rgba(54, 162, 235, 0.8);
                            transition: height 0.3s ease, background-color 0.3s ease; /* Transition for height and color */
                            border-radius: 5px; /* Rounded corners for the bars */
                        }

                        .bar:hover {
                            background-color: rgba(54, 162, 235, 1); /* Darker color on hover */
                        }

                        .label {
                            display: block;
                            margin-top: 10px;
                            font-size: 14px;
                            color: #333; /* Darker color for the label */
                        }
                    </style>
                </div>
            </div>
        </div>
    </div>
@endsection
