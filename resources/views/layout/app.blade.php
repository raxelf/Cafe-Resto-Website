<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>OPPA BOX - @yield('title')</title>

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
</head>
<body>
    <div class="container-fluid vh-100">
        <div class="row h-100">
            <div class="col-3 col-lg-2 d-md-block d-lg-block position-relative" id="sidebar" style="transition: all 0.3s ease; border-radius: 0px 40px 40px 0px;background: #FFF;box-shadow: 0px 0px 12px 0px rgba(0, 0, 0, 0.25);">
                <a href="/admin/dashboard" class="m-4 m-md-5 d-flex justify-content-center align-items-center">
                    <div class="" id="brand">
                        <img src="{{ asset('img/brand.webp') }}" alt="" width="100px" height="40px">
                    </div>
                </a>
                <a href="/admin/dashboard" class="sidebar-link mx-auto d-flex justify-content-center align-items-center {{ request()->is('admin/dashboard') ? 'active' : '' }}" style="cursor: pointer;width: 90%;height:87px;border-radius: 20px;text-decoration: none;color:#1e1e1e;">
                    <div class="mx-auto w-50 d-flex justify-content-center align-items-center gap-3">
                        <i class="my-auto bi bi-house" style="font-size: 30px;"></i>
                        <span class="sidebar-text">Dashboard</span>
                    </div>
                </a>
                <a href="/admin/data/kategori" class="sidebar-link mx-auto d-flex justify-content-center {{ request()->is('admin/data/*') ? 'active' : '' }}" style="cursor: pointer;width: 90%;height:87px;border-radius: 20px;background: #Fff;text-decoration: none;color:#1e1e1e;">
                    <div class="w-50 d-flex justify-content-center align-items-center gap-3">
                        <i class="bi bi-clipboard-data" style="font-size: 30px;"></i>
                        <span class="sidebar-text">Data Master</span>
                    </div>
                </a>
                <a href="/admin/data/kategori" id="datakategori" class="d-none sidebar-link mx-auto d-flex justify-content-center {{ request()->is('admin/data/kategori*') ? 'activedata' : '' }}" style="cursor: pointer;width: 90%;height:87px;border-radius: 20px;background: #Fff;text-decoration: none;color:#1e1e1e;">
                    <div class="w-50 d-flex justify-content-center align-items-center gap-3">
                        <i class="bi bi-tag" style="font-size: 30px;"></i>
                        <span class="sidebar-text">Data Kategori</span>
                    </div>
                </a>
                <a href="/admin/data/barang" id="databarang" class="d-none sidebar-link mx-auto d-flex justify-content-center {{ request()->is('admin/data/barang*') ? 'activedata' : '' }}" style="cursor: pointer;width: 90%;height:87px;border-radius: 20px;background: #Fff;text-decoration: none;color:#1e1e1e;">
                    <div class="w-50 d-flex justify-content-center align-items-center gap-3">
                        <i class="bi bi-box2" style="font-size: 30px;"></i>
                        <span class="sidebar-text">Data Barang</span>
                    </div>
                </a>
                <a href="/admin/pesanan" class="sidebar-link mx-auto d-flex justify-content-center {{ request()->is('admin/pesanan') ? 'active' : '' }}" style="cursor: pointer;width: 90%;height:87px;border-radius: 20px;background: #Fff;text-decoration: none;color:#1e1e1e;">
                    <div class="w-50 d-flex justify-content-center align-items-center gap-3">
                        <i class="bi bi-clipboard" style="font-size: 30px;"></i>
                        <span class="sidebar-text">Pesanan</span>
                    </div>
                </a>
                <a href="/admin/laporan" class="sidebar-link mx-auto d-flex justify-content-center {{ request()->is('admin/laporan') ? 'active' : '' }}" style="cursor: pointer;width: 90%;height:87px;border-radius: 20px;background: #Fff;text-decoration: none;color:#1e1e1e;">
                    <div class="w-50 d-flex justify-content-center align-items-center gap-3">
                        <i class="bi bi-journal-bookmark" style="font-size: 30px;"></i>
                        <span class="sidebar-text">Laporan</span>
                    </div>
                </a>
                <a href="/admin/logout" class="mb-4 sidebar-link mx-auto d-flex justify-content-center" style="cursor: pointer;width: 90%;height:87px;border-radius: 20px;background: #Fff;text-decoration: none;color:#1e1e1e;">
                    <div class="w-50 d-flex justify-content-center align-items-center gap-3">
                        <i class="bi bi-box-arrow-left" style="font-size: 30px;"></i>
                        <span class="sidebar-text">Logout</span>
                    </div>
                </a>
            </div>
            <div class="col-7 col-lg-10 order-md-last" id="content" style="transition: all 0.3s ease;">
                <div class="d-flex justify-content-between">
                    <div class="d-none d-md-flex justify-content-center align-items-center" style="width: 50px;height:50px; margin: 20px;cursor: pointer;border-radius: 20px;border: 1px solid #1e1e1e;">
                        <i id="toggleSidebar" class="bi bi-x-lg" style="font-size: 26px"></i>
                    </div>

                    <div class="d-flex">
                        <div class="d-flex justify-content-center align-items-center gap-3" style="width: 160px;height:50px; margin: 20px;cursor: pointer;border-radius: 20px;border: 1px solid #1e1e1e;">
                            <img src="{{ asset('img/user-placeholder.webp') }}" width="32" style="border-radius: 20px" alt="">
                            <span>Hi, Admin!</span>
                        </div>
                    </div>
                </div>

                <div class="w-100 container">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function(){

            function checkScreenWidth() {
                if (window.innerWidth <= 768) {
                    $("#sidebar").addClass("collapsed col-1 col-lg-1");
                    $("#toggleSidebar").removeClass("bi-x-lg").addClass("bi-list");
                    $("#content").addClass("col-9 col-lg-11 order-last");
                    $(".sidebar-text").hide();
                    $("#brand img").addClass("brand-collapsed-mbl");
                } else{
                    $("#toggleSidebar").click(function(){
                        $("#sidebar").toggleClass("collapsed");
                        if ($("#sidebar").hasClass("collapsed")) {
                            $("#toggleSidebar").removeClass("bi-x-lg").addClass("bi-list");
                        } else {
                            $("#toggleSidebar").removeClass("bi-list").addClass("bi-x-lg");
                        }
                        $("#sidebar").toggleClass("col-1 col-lg-2 col-1 col-lg-1");
                        $("#content").toggleClass("col-11 col-lg-10 col-11 col-lg-11 order-last");
                        $(".sidebar-text").toggle();
                        $("#brand img").toggleClass("brand-collapsed");
                    });
                }
            }
            
            checkScreenWidth();
            
            $(window).resize(function() {
                checkScreenWidth();
            });

            const currentURL = window.location.pathname;

            if (currentURL.startsWith('/admin/data/kategori') || currentURL.startsWith('/admin/data/barang')) {
                document.getElementById('datakategori').classList.remove('d-none');
                document.getElementById('databarang').classList.remove('d-none');
            }
        });
    </script>

    <script>
        function getCookie(name) {
            var nameEQ = name + "=";
            var ca = document.cookie.split(';');
            for(var i=0;i < ca.length;i++) {
                var c = ca[i];
                while (c.charAt(0)==' ') c = c.substring(1,c.length);
                if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
            }
            return null;
        }
    </script>

    @stack('js')
    
</body>
</html>
