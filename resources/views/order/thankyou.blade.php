<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>OPPA BOX - Your Order</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg" style="z-index: 1000">
        <div class="container-fluid px-4">
            <div class="d-flex align-items-center gap-3 py-2 px-4" style="border-radius: 20px;border: 1px solid #1e1e1e;background: #FFF;">
                <a class="navbar-brand" href="/">
                    <img src="{{ asset('img/brand.webp') }}" alt="oppabox" width="90px" height="35px">
                </a>
                <a href="/" class="nav-link {{ request()->is('/') ? 'active' : '' }}" aria-current="page">Home</a>
                <a href="/menu" class="nav-link {{ request()->is('menu') ? 'active' : '' }}">Menu</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid p-4 mb-5">
        <div class="w-100 p-4" style="border-radius: 20px;background: #FFF;box-shadow: 0px 0px 12px 0px rgba(0, 0, 0, 0.25);">
            <div class="row justify-content-between align-items-center">
                <div class="col-md-6">
                    <div class="text-dark" style="font-size: 24px; font-weight: 600;">Your Order</div>
                    <div style="color: rgba(30, 30, 30, 0.25);font-size: 18px;font-weight: 700;">
                        INVOICE #<span id="invoice">{{ $order->invoice }}</span> <i class="bi bi-copy btn" onclick="copyInvoice()"></i>
                    </div>
                </div>
    
                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                    @if ($order->payment_method != 'cash' && $order->status_pembayaran == 'UNPAID')
                        <span class="px-4 py-2 rounded text-dark font-weight-400" style="border: 1px solid #1E1E1E; text-transform: uppercase;">Menunggu Pembayaran</span>
                    @elseif($order->status == 'Baru' || $order->status == 'Dikemas')
                        <span class="px-4 py-2 rounded text-dark font-weight-400" style="border: 1px solid #1E1E1E;">PROCESSING</span>
                    @elseif ($order->status == 'Selesai')
                        <span class="px-4 py-2 rounded font-weight-400" style="border: 1px solid #04BC00;color:#04BC00;text-transform: uppercase;">{{ $order->status }}</span>
                    @else
                        <span class="px-4 py-2 rounded text-dark font-weight-400" style="border: 1px solid #1E1E1E;text-transform: uppercase;">{{ $order->status }}</span>
                    @endif
                </div>
            </div>
            <hr>

            <div class="p-4">
                <span style="color: #1E1E1E; font-size: 16px; font-weight: 400;">
                    @if ($order->payment_method != 'cash' && $order->status_pembayaran == 'UNPAID')
                        Menunggu Pembayaran sebelum pesananmu di-proses!
                    @elseif ($order->status == 'Baru')
                        Pesananmu telah diterima dan segera akan dikemas dengan cermat.
                    @elseif ($order->status == 'Dikemas')
                        Pesananmu sedang dikemas dengan cermat dan akan segera dikirim.
                    @elseif ($order->status == 'Diantar')
                        Pesananmu sedang dalam perjalanan dan akan segera sampai di tujuan.
                    @elseif ($order->status == 'Selesai')
                        Pesananmu telah sampai dengan selamat. Terima kasih atas pembelianmu!
                    @endif
                </span>
            </div>
            <div class="card">
                <div class="progress-track">
                    <ul class="list-unstyled" id="progressbar">
                        <li class="step0 @if($order->payment_method !== 'cash' && $order->status_pembayaran == 'UNPAID') @elseif($order->status == 'Baru' || $order->status == 'Dikemas' || $order->status == 'Diantar' || $order->status == 'Selesai') active @endif" id="step1">
                            Order Received
                        </li>
                        <li class="step0 @if($order->status == 'Dikemas' || $order->status == 'Diantar' || $order->status == 'Selesai') active @endif text-center" id="step2">Dikemas</li>
                        <li class="step0 @if($order->status == 'Diantar' || $order->status == 'Selesai') active @endif text-right" id="step3">Diantar</li>
                        <li class="step0 @if($order->status == 'Selesai') active @endif text-right" id="step4">Sampai di tujuan</li>
                    </ul>
                </div>
            </div>

        </div>

        <div class="w-100 p-4 mt-4" style="border-radius: 20px;background: #FFF;box-shadow: 0px 0px 12px 0px rgba(0, 0, 0, 0.25);">
            <div class="row justify-content-between align-items-center">
                <div class="col-md-6">
                    <div class="text-dark" style="font-size: 24px; font-weight: 600;">Order Details</div>
                </div>
    
                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                    @if ($order->status_pembayaran == 'paid' || $order->status_pembayaran == 'settled')
                        <span class="px-4 py-2 rounded font-weight-400" style="border: 1px solid #04BC00;color:#04BC00; text-transform: uppercase;">paid</span>
                    @elseif ($order->payment_method == 'cash')
                        <span class="px-4 py-2 rounded font-weight-400" style="border: 1px solid #ff0066;color:#ff0066; text-transform: uppercase;">{{ $order->payment_method }}</span>
                    @else
                        <span class="px-4 py-2 rounded text-dark font-weight-400" style="border: 1px solid #1E1E1E; text-transform: uppercase;">{{ $order->status_pembayaran }}</span>
                    @endif
                </div>
            </div>
            <hr>

            <div class="">
                <div class="row">
                    <div class="col-md-12">
                        @if($orderDetails->isNotEmpty())
                            @foreach($orderDetails as $orderDetail)
                                <div class="row justify-content-between mb-2 align-items-center px-4">
                                    <div class="col-md-6 col-lg-8">
                                        <div class="d-flex gap-3 align-items-center">
                                            <img src="{{ asset('uploads/Products/') . '/' . $orderDetail->product->gambar }}" alt="{{ $orderDetail->product->name }}" class="img-fluid" width="120px">
                                            <span style="color: #1e1e1e; font-size: 18px; font-weight: 400;">{{ $orderDetail->product->nama_barang }}</span>
                                        </div>
                                    </div>
            
                                    <div class="col-md-6 col-lg-4">
                                        <div class="d-flex flex-column align-items-end">
                                            <div class="d-flex justify-content-center mb-2">
                                                <span class="total-price" data-price="{{ $orderDetail->product->harga - $orderDetail->product->diskon }}" style="color: #1E1E1E; font-size: 20px; font-weight: 400;">
                                                    Rp {{ number_format(($orderDetail->product->harga - $orderDetail->product->diskon) * $orderDetail->jumlah, 0, ',', '.') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                            @endforeach
                        @endif
                    </div>
                </div>
            
                <div class="row d-md-flex justify-content-between">
                    <div class="col-md-6">
                        <p style="color: #1e1e1e; font-size: 20px; font-weight: 600;">Payment</p>
                        <p class="d-flex gap-3 align-items-center" style="color: #1e1e1e; font-size: 18px; font-weight: 500; text-transform: uppercase;">
                            @if ($order->payment_method == 'cash')
                                <i class="bi bi-cash fs-4"></i>Cash On Delivery
                            @elseif ($order->payment_method == 'QRIS')
                                <i class="bi bi-qr-code-scan fs-4"></i>
                                QRIS
                            @elseif ($order->payment_method == 'DANA')
                                <img src="{{ asset('img/dana-logo.webp') }}" alt="dana" width="30px" style="border-radius: 20px">
                                DANA
                            @elseif ($order->payment_method == 'SHOPEEPAY')
                                <img src="{{ asset('img/spay-logo.webp') }}" alt="shopeepay" width="30px" >
                                SHOPEEPAY
                            @elseif ($order->payment_method == 'BRI')
                                <img src="{{ asset('img/logo_bank_bri.webp') }}" alt="bri" width="40px" >
                                BRI
                            @elseif ($order->payment_method == 'BNI')
                                <img src="{{ asset('img/bni-logo.webp') }}" alt="bni" width="30px" >
                                BNI
                            @else
                                {{ $order->payment_method }}
                            @endif
                        </p>
                        <p style="color: #F6B805; font-size: 20px; font-weight: 500;">
                            @if ($order->payment_method === "DANA" || $order->payment_method === "SHOPEEPAY")
                                Rp {{ number_format($order->grand_total*0.04 + $order->grand_total, 0, ',', '.') }}
                            @elseif ($order->payment_method === "QRIS")
                                Rp {{ number_format($order->grand_total*0.02 + $order->grand_total, 0, ',', '.') }}
                            @elseif ($order->payment_method === "BRI" || $order->payment_method === "BNI")
                                Rp {{ number_format($order->grand_total + 5000, 0, ',', '.') }}
                            @else
                                Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                            @endif
                        </p>

                        
                        @if ($order->payment_method === "cash")
                        
                        @elseif ($order->status_pembayaran === "UNPAID")
                            <div class="d-flex mt-3 mb-3">
                                <button id="payNowButton" data-checkout-link="{{ $order->checkout_link }}" type="submit" class="btn d-flex gap-3 justify-content-center align-items-center payNowButton" style="border-radius:18px ;background: #F6B805;color: #FFF;text-align: center;font-size: 18px;font-weight: 600;">
                                    <i class="bi bi-wallet fs-6"></i>
                                    Bayar Sekarang
                                </button>
                            </div>
                        @endif
                    </div>
            
                    <div class="col-md-3">
                        <p style="color: #1e1e1e; font-size: 20px; font-weight: 600;">Delivery</p>
                        <div class="d-flex flex-column">
                            <span style="color: rgba(30, 30, 30, 0.25); font-size: 18px; font-weight: 500;">Address</span>
                            <span style="color: #1E1E1E; font-size: 16px; font-weight: 600;">
                                {{ $order->nama }}
                            </span>
                            <span style="color: #1E1E1E; font-size: 16px; font-weight: 400;">
                                {{ $order->alamat }} ({{ $order->detail_alamat }})
                            </span>
                            <span style="color: #1E1E1E; font-size: 16px; font-weight: 400;">
                                {{ $order->nowhatsapp }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="mt-5" style="border-radius: 20px 20px 0px 0px;background: #F6B805;">
        <div class="container-fluid p-5">
            <div class="row">
                <div class="col-md-4 mt-4">
                    <img src="{{ asset('img/brand-circle.webp') }}" alt="OppaBox Logo" width="150px" height="auto">
                </div>

                <div class="col-md-2 mt-4">
                    <h5 style="color: #FFF;font-size: 24px;font-weight: 700;">Navigasi</h5>
                    <div class="mt-4 d-flex flex-column gap-2">
                        <a href="/" class="nav-link" style="color: #FFF;font-size: 20px;;font-weight: 400;">Home</a>
                        <a href="/menu" class="nav-link" style="color: #FFF;font-size: 20px;;font-weight: 400;">Menu</a>
                    </div>
                </div>

                <div class="col-md-4 mt-4">
                    <h5 style="color: #FFF;font-size: 24px;font-weight: 700;">Kontak</h5>
                    <div class="d-flex fs-4 gap-4 mt-4" style="color: #FFF">
                        <a href="" class="nav-link"><i class="bi bi-whatsapp"></i></a>
                        <a href="https://www.instagram.com/oppa_box/" target="_blank" class="nav-link"><i class="bi bi-instagram"></i></a>
                        <a href="https://maps.app.goo.gl/FzNja8iFaREZHqrv5" target="_blank" class="nav-link"><i class="bi bi-map"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-center p-5 align-items-middle" style="color: #FFF;font-size: 14px;font-weight: 400;">
            <p>© 2023 Oppa Box. All rights reserved</p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script>
        $(".payNowButton").on("click", function () {
            var checkoutLink = $(this).data('checkout-link');
            window.location.href = checkoutLink;
        });

        function copyInvoice() {
            var copyText = document.getElementById("invoice");

            var range = document.createRange();
            range.selectNode(copyText);
            window.getSelection().removeAllRanges();
            window.getSelection().addRange(range);

            document.execCommand('copy');
            
            window.getSelection().removeAllRanges();

            alert('Invoice berhasil di-copy');
        }
    </script>
</body>
</html>