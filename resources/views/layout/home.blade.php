<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <title>@yield('title', 'OPPA BOX : Cafe & Resto ala Korea.')</title>
    <meta name="description" content="OPPA BOX : Temukan keindahan rasa dan estetika yang memikat di setiap sudut, hanya di OPPA BOX">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="rating" content="general" >
    <meta name="geo.region" content="ID" >
    <meta name="geo.country" content="id" >
    <meta content="true" name="HandheldFriendly">
    <meta content="width" name="MobileOptimized">
    <meta property="og:type" content="website" >
    <meta property="og:locale" content="ID" >
    <meta property="og:title" content="OPPA BOX - Cafe & Resto ala Korea.">
    <meta property="og:description" content="OPPA BOX : Temukan keindahan rasa dan estetika yang memikat di setiap sudut, hanya di OPPA BOX">
    <meta property="og:url" content="https://raxelf.my.id/">
    <meta property="og:site_name" content="OPPA BOX">
    <meta property="og:image" content="https://raxelf.my.id/public/img/brand.webp">
    <link rel="canonical" href="@yield('canonical-url', 'https://raxelf.my.id/')">

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg position-fixed" style="z-index: 1000">
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

    <div id="checkoutCard" class="d-none position-fixed w-100 bottom-0 p-3 p-md-5 d-flex flex-column flex-md-row align-items-center overflow-auto gap-3 gap-md-5" style="height: 300px; border-radius: 40px 40px 0 0; background: #fff; box-shadow: 0 0 12px 0 rgba(0, 0, 0, 0.25); z-index: 99">
        <div class="d-flex align-items-center gap-5" >
            <img class="img-fluid" id="checkoutImage" alt="" width="150px" height="50%">
        </div>
        <div class="h-100" style="border-left: 3px solid rgba(30, 30, 30, 0.11);"></div>
        <div>
            <input type="hidden" id="checkoutID">
            <p style="color: #1e1e1e;font-size: 20px;font-weight: 400;" id="checkoutProductName"></p>
            <p style="color: #F6B805;font-size: 24px;font-weight: 400;" id="checkoutProductPrice"></p>
            <div class="d-flex justify-content-between align-items-center" style="width:200px;border-radius: 20px; background: #FFF; box-shadow: 0px 0px 12px 0px rgba(0, 0, 0, 0.25);">
                <div class="px-3 py-2" style="border-radius: 20px; background: #F6B805; color: #FFF; text-align: center; font-size: 18px; font-weight: 400;">Qty</div>
                <input type="number" class="px-1 d-flex my-auto" id="quantity" value="1" min="1" max="32" style="border: none;text-align:center;">
                <div class="btn" onclick="increaseQuantity()">
                    <i class="bi bi-plus-lg fs-5" style="color: #F6B805;"></i>
                </div>
            </div>
            <div class="mt-4 d-sm-flex d-md-flex gap-3 flex-wrap justify-content-center">
                <form>
                    <button id="addToCartButton" class="btn px-5 py-2 py-md-4 d-flex gap-2 align-items-center mb-2" style="border-radius:20px ;border: 1px solid #F6B805;color: #F6B805;text-align: center;font-size: 18px;font-weight: 600;">
                        <i class="bi bi-cart-plus-fill"></i>
                        Add to Cart
                    </button>
                </form>
                <button id="orderNowButton" class="btn px-5 py-2 d-flex gap-2 align-items-center mb-2" style="border-radius:20px ;background: #F6B805;text-align: center;font-size: 18px;font-weight: 600;">
                    <i class="bi bi-basket2-fill fs-3 fs-md-4" style="color: #fff"></i>
                    <span style="color: #fff; font-size: 18px; font-weight: 600;">Order Now</span>
                </button>
            </div>
        </div>
    </div>

    <div id="cartOrder" class="d-none overflow-auto position-fixed w-100 bottom-0 p-3 p-md-5 d-flex flex-column flex-md-row align-items-center justify-content-between gap-3 gap-md-5" style="height: 150px; border-radius: 40px 40px 0 0; background: #fff; box-shadow: 0 0 12px 0 rgba(0, 0, 0, 0.25); z-index: 98">
        <div class="d-flex align-items-center gap-5">
            <p class="m-0" style="color: #1e1e1e; font-size: 18px; font-weight: 600;">Total Orders</p>
            <div class="d-flex flex-column">
                <span id="totalPrice" style="color: #f6b805; font-size: 18px; font-weight: 600;">Rp 0</span>
                <span id="productList" style="color: rgba(30, 30, 30, 0.20); font-size: 14px; font-weight: 600;"></span>
            </div>
        </div>
    
        <div onclick="orderClicked()" class="btn px-5 px-md-5 py-2 py-md-3 d-flex gap-2 align-items-center" style="border-radius: 20px; background: #f6b805;">
            <i class="bi bi-basket2-fill fs-3 fs-md-4" style="color: #fff"></i>
            <span style="color: #fff; font-size: 14px; font-weight: 600;">Order</span>
        </div>
    </div>

    <div class="container-fluid">

        @yield('content')

    </div>

    <footer style="border-radius: 20px 20px 0px 0px;background: #F6B805;">
        <div class="container-fluid p-5">
            <div class="row">
                <div class="col-md-4 mt-4">
                    <img src="{{ asset('img/brand-circle.webp') }}" alt="OppaBox Logo" width="150px" height="100%">
                </div>

                <div class="col-md-2 mt-4">
                    <p style="color: #FFF;font-size: 24px;font-weight: 700;">Navigasi</p>
                    <div class="mt-4 d-flex flex-column gap-2">
                        <a href="/" class="nav-link" style="color: #FFF;font-size: 20px;;font-weight: 400;">Home</a>
                        <a href="/menu" class="nav-link" style="color: #FFF;font-size: 20px;;font-weight: 400;">Menu</a>
                    </div>
                </div>

                <div class="col-md-4 mt-4">
                    <p style="color: #FFF;font-size: 24px;font-weight: 700;">Kontak</p>
                    <div class="d-flex fs-4 gap-4 mt-4" style="color: #FFF">
                        <a href="https://api.whatsapp.com/send?phone=6281374985375" target="_blank" class="nav-link" aria-label="WhatsApp"><i class="bi bi-whatsapp"></i></a>
                        <a href="https://www.instagram.com/oppa_box/" aria-label="Instagram" target="_blank" class="nav-link"><i class="bi bi-instagram"></i></a>
                        <a href="https://maps.app.goo.gl/FzNja8iFaREZHqrv5" aria-label="Map" target="_blank" class="nav-link"><i class="bi bi-map"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-center p-5 align-items-middle" style="color: #FFF;font-size: 14px;font-weight: 400;">
            <p>© 2023 Oppa Box. All rights reserved</p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script>
        function formatRupiah(angka) {
            var number_string = angka.toString();
            var sisa = number_string.length % 3;
            var rupiah = number_string.substr(0, sisa);
            var ribuan = number_string.substr(sisa).match(/\d{3}/g);

            if (ribuan) {
                var separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            return rupiah;
        }

        var prevScrollpos = window.pageYOffset;
        var isScrollingDown = false;

        window.onscroll = function() {
            var currentScrollPos = window.pageYOffset;
            const cartOrder = document.getElementById("cartOrder");

            if (prevScrollpos > currentScrollPos) {
                if(cartAvailable){
                    isScrollingDown = false;
                    document.getElementById("cartOrder").classList.remove("slide-down");
                    document.getElementById("cartOrder").classList.remove("d-none");
                    document.getElementById("cartOrder").classList.add("slide-up");
                }
            } else {
                if(cartAvailable){
                    isScrollingDown = true;
                    document.getElementById("cartOrder").classList.remove("slide-up");
                    document.getElementById("cartOrder").classList.add("slide-down");
    
                    cartOrder.addEventListener('animationend', function () {
                        if (isScrollingDown) {
                            document.getElementById("cartOrder").classList.remove("slide-down");
                            document.getElementById("cartOrder").classList.add("d-none");
                        }
                    }, { once: true });
                }
            }

            prevScrollpos = currentScrollPos;
        }

        let quantityInput = document.getElementById("quantity");
    
        quantityInput.addEventListener("input", function() {
            let enteredValue = parseInt(quantityInput.value);
    
            if (isNaN(enteredValue) || enteredValue < 1) {
                quantityInput.value = 1;
            } else if (enteredValue > 32) {
                quantityInput.value = 32;
            }
        });
    
        function increaseQuantity() {
            let currentQuantity = parseInt(quantityInput.value);
            let newQuantity = currentQuantity + 1;
    
            if (newQuantity <= 32) {
                quantityInput.value = newQuantity;
            }
        }

        function addToCartClicked() {
            const productId = document.getElementById('checkoutID').value;
            const quantity = document.getElementById('quantity').value;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            $.ajax({
                url: '/add-to-cart',
                type: 'POST',
                data: {
                    _token: csrfToken,
                    product_id: productId,
                    quantity: quantity,
                },
                success: function(response) {
                    if(data.success){
                        alert('Item successfully added to cart');

                        location.reload();
                    }
                },
                error: function(error) {
                    alert('Error adding to cart. Please try again.');
                }
            });

            closeCheckoutCard();
        }

        document.addEventListener('DOMContentLoaded', function () {
            const addToCartButton = document.getElementById('addToCartButton');
            if (addToCartButton) {
                addToCartButton.addEventListener('click', addToCartClicked);
            }
        });

        function closeCheckoutCard() {
            const checkoutCard = document.getElementById('checkoutCard');
            checkCartItems()
            checkoutCard.classList.remove('slide-up');
            checkoutCard.classList.add('slide-down');
            checkoutCard.addEventListener('animationend', function () {
                checkoutCard.classList.add('d-none');
                checkoutCard.classList.remove('slide-down');
            }, { once: true });
        }

        let cartAvailable = false;

        function checkCartItems() {
            $.ajax({
                url: '/get-cart-items',
                type: 'GET',
                success: function(response) {
                    if (response.totalPrice > 0) {
                        showCartSummary(response.totalPrice, response.productList);
                        cartAvailable = true;
                    } else {
                        cartAvailable = false;
                        hideCartSummary();
                    }
                },
                error: function(error) {
                    console.error('Error checking cart items:', error);
                }
            });
        }

        function showCartSummary(totalPrice, productList) {
            const totalOrdersElement = document.getElementById('cartOrder');
            totalOrdersElement.classList.add('slide-up');
            totalOrdersElement.classList.remove('slide-down');
            if (totalOrdersElement) {
                totalOrdersElement.classList.remove('d-none');
                totalOrdersElement.classList.add('d-flex');

                const totalPriceElement = totalOrdersElement.querySelector('#totalPrice');
                const productListElement = totalOrdersElement.querySelector('#productList');

                if (totalPriceElement && productListElement) {
                    totalPriceElement.textContent = `Rp ${totalPrice.toLocaleString()}`;
                    
                    const displayedProducts = productList.slice(0, 2);

                    const productListHTML = displayedProducts.map(product => {
                        return `${product.name} (Qty: ${product.quantity}, Subtotal: Rp ${product.subtotal.toLocaleString()})`;
                    }).join(', ');

                    if (productList.length > 2) {
                        productListElement.innerHTML = `${productListHTML} <span class="ellipsis">...</span>`;
                    } else {
                        productListElement.textContent = productListHTML;
                    }
                }
            }
        }

        function hideCartSummary() {
            const totalOrdersElement = document.getElementById('cartOrder');
            if (totalOrdersElement) {
                totalOrdersElement.classList.add('d-none');

                totalOrdersElement.classList.remove('slide-up');
                totalOrdersElement.classList.add('slide-down');
                totalOrdersElement.addEventListener('animationend', function () {
                    totalOrdersElement.classList.add('d-none');
                    totalOrdersElement.classList.remove('slide-down');
                }, { once: true });
            }
        }

        checkCartItems()

        function orderNowClicked() {
            const productId = document.getElementById('checkoutID').value;
            const quantity = document.getElementById('quantity').value;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            $.ajax({
                url: '/add-to-cart',
                type: 'POST',
                data: {
                    _token: csrfToken,
                    product_id: productId,
                    quantity: quantity,
                },
                success: function(response) {
                    if(response.success){

                        window.location.href = '/order-details/' + response.orderId;
                    }
                },
                error: function(error) {
                    alert('Error adding to cart. Please try again.');
                }
            });
        }
        document.getElementById('orderNowButton').addEventListener('click', orderNowClicked);

        function orderClicked(){
            $.ajax({
                url: '/get-cart-items',
                type: 'GET',
                success: function(response) {
                    window.location.href = '/order-details/' + response.orderId;
                },
                error: function(error) {
                    console.error('Error checking cart items:', error);
                }
            });
        }
    </script>
    
    @stack('js')
</body>
</html>