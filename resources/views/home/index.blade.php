@extends('layout.home')

@section('content')
    <div class="row">
        <div class="col-md-8 p-4">
            <div class="row">
                <div class="col-md-6 order-md-1 d-none d-md-flex">
                    <div class="position-relative" style="width:100%;">
                        <img class="img-fluid position-relative" src="{{ asset('img/opabox.webp') }}" alt="oppabox" width="500px" height="auto" style="top:50px;z-index: 2">
                        <img class="position-relative" src="{{ asset('img/Rectangle 4.webp') }}" alt="" style="top: -150px;z-index: 1;">
                    </div>
                </div>
                
                <div class="col-md-6 order-md-2">
                    <div class="position-relative d-flex justify-content-center align-items-center" style="width:100%; height:400px; border-radius: 0px 17px 17px 17px;">
                        <img id="productImage" class="img-fluid position-relative" width="70%" height="100%" style="z-index: 2;">
                    </div>
                </div>
            </div>
                
            <div class="d-md-flex gap-5 w-sm-100">
                <div class="position-relative p-4 bannerText mb-5" style="width:100%;border-radius: 0px 17px 17px 17px;background: #FFf;z-index: 3; height:150px;">
                    <div class="d-flex flex-column">
                        <div class="d-flex mx-auto">
                            <span id="twoLetters" style="color: #FFBE00;font-family: Shojumaru;font-size: 28px;font-weight: 400;"></span>
                            <span id="productName" style="color: #1E1E1E;font-family: Shojumaru;font-size: 28px;font-weight: 400;"></span>
                        </div>
                    </div>
                </div>

                <div id="pesanSekarangButton" class="position-relative d-flex justify-content-center align-items-center bannerText" style="width:100%;z-index: 3;">
                    <div class="btn btn-warning w-100 d-flex align-items-center justify-content-center" style="height:90px;color: #FFF;text-align: center;font-size: 24px;font-weight: 600;border-radius: 40px;">Pesan Sekarang</div>
                </div>
            </div>
        </div>

        <div class="col-md-4 p-4" style="z-index: 3">
            <div class="row" style="border-radius: 20px;background: #EAEAEA;">
                <div class="p-2">
                    <div class="d-flex p-2 justify-content-center" style="border-radius: 20px;background: #FFF;box-shadow: 0px 4px 12px 0px rgba(0, 0, 0, 0.25);color: #F6B805;text-align: center;font-size: 18px;font-weight: 700;">
                        Yuk Cobain!
                    </div>
                    @foreach($additionalProducts as $additionalProduct)
                        <div class="btn d-flex p-4 mt-2 mb-4 additional-product" data-discount="{{ $additionalProduct->diskon }}" data-id="{{ $additionalProduct->id }}" style="border-radius: 20px;background: #FFF;box-shadow: 0px 4px 12px 0px rgba(0, 0, 0, 0.25);">
                            <div style="position: relative; width: 150px; height: 100px; border-radius: 90px; background: #F6B805">
                                <img class="img-fluid" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);" src="{{ asset('uploads/Products/'.$additionalProduct->gambar) }}" width="100%" height="100%" alt="">
                            </div>
                            
                            <div class="d-flex flex-column justify-content-center w-100 gap-3">
                                <div class="d-flex flex-column">
                                    <span class="product-name" style="color: #1E1E1E;text-align: center;font-size: 18px;font-weight: 400;">{{ $additionalProduct->nama_barang }}</span>
                                </div>
                                <p class="product-price" style="color: #F6B805;text-align: center;font-size: 18px;font-weight: 400;">Rp {{ number_format($additionalProduct->harga - $additionalProduct->diskon , 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="row mt-4">
                <div class="d-flex flex-wrap gap-3 justify-content-end">
                    <img src="{{ asset('img/brand.webp') }}" alt="OPPABOX" width="120px" height="100%">
                    <a href="https://www.instagram.com/oppa_box/" aria-label="Instagram" target="_blank" class="btn fs-4" style="color: #F6B805">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="https://maps.app.goo.gl/FzNja8iFaREZHqrv5" aria-label="Map" target="_blank" class="btn fs-4" style="color: #F6B805">
                        <i class="bi bi-map"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>


    {{-- Maps --}}
    <div class="mt-5 mapOppa position-relative" style="z-index: 3">
        <h3 style="color: #F6B805;text-align: center;font-size: 40px;font-weight: 700;">Yuk! Kunjungi OPPA BOX</h3>
        <div class="p-4 d-flex justify-content-center" style="border-radius: 40px;">
            <iframe class="w-100" style="border-radius: 40px;" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15957.045919669155!2d100.3967274!3d-0.9572635!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2fd4b9982a31ae27%3A0x191c5ddde0bb1639!2sOPPA%20BOX!5e0!3m2!1sen!2sid!4v1703064548042!5m2!1sen!2sid" width="800" height="600" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="OPPA BOX MAP"></iframe>
        </div>
    </div>

    {{-- Product --}}
    <div class="p-4 position-relative productTop">
        <div class="d-flex justify-content-between">
            <div style="border-bottom: 1px solid #F6B805; border-width: 3px">
                <p style="color: #1E1E1E;text-align: center;font-size: 24px;font-weight: 500;">Top <span style="color: #F6B805">Foods</span></p>
            </div>

            <a href="{{ url('/menu?category=Foods') }}" class="nav-link d-flex gap-2 align-items-center">
                <p class="d-flex align-items-center mb-0" style="color: #1e1e1e; font-size: 18px; font-weight: 400;">Lihat Semua</p>
                <i class="bi bi-chevron-right" style="font-size: 18px; color: #F6B805;"></i>
            </a>
        </div>

        <div class="row mt-3">
            @foreach($foodProducts as $food)
                <div class="col-md mb-2 btn additional-product" data-discount="{{ $food->diskon }}" data-id="{{ $food->id }}">
                    <div style="border-radius: 20px;border: 1px solid rgba(0, 0, 0, 0.24);background: #FFF;">
                        <div class="d-flex justify-content-center" style="height: 220px">
                            <img class="img-fluid" src="{{ asset('uploads/Products/'.$food->gambar) }}" width="240px" height="100%" alt="{{ $food->nama_barang }}">
                        </div>
                        <p class="product-name" style="color: #1e1e1e;text-align: center;font-size: 20px;font-weight: 400;">{{ $food->nama_barang }}</p>
                        @if($food->diskon > 0)
                            <div class="d-flex gap-2 justify-content-center">
                                <span class="outer">
                                    <span class="inner">Rp {{ number_format($food->harga, 0, ',', '.') }}</span>
                                </span>
                                <span class="product-price" style="color: #F6B805;text-align: center;font-size: 20px;font-weight: 400;">Rp {{ number_format($food->harga - $food->diskon, 0, ',', '.') }}</span>
                            </div>
                        @else
                            <span class="product-price d-flex justify-content-center" style="color: #F6B805;text-align: center;font-size: 20px;font-weight: 400;">Rp {{ number_format($food->harga, 0, ',', '.') }}</span>
                        @endif
                        <div class="btn w-100 mt-4 p-3" style="border-radius: 0px 0px 20px 20px;border: 1px solid rgba(0, 0, 0, 0.24);background: #F6B805;">
                            <div class="d-flex gap-2 justify-content-center" style="color: #fff">
                                <i class="bi bi-cart-plus-fill"></i>
                                <span style="color: #FFF;text-align: center;font-size: 18px;font-weight: 400;">Add to Cart</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-between mt-5">
            <div style="border-bottom: 1px solid #F6B805; border-width: 3px">
                <p style="color: #1E1E1E;text-align: center;font-size: 24px;font-weight: 500;">Top <span style="color: #F6B805">Drinks</span></p>
            </div>

            <a href="{{ url('/menu?category=Drinks') }}" class="nav-link d-flex gap-2 align-items-center">
                <p class="d-flex align-items-center mb-0" style="color: #1e1e1e; font-size: 18px; font-weight: 400;">Lihat Semua</p>
                <i class="bi bi-chevron-right" style="font-size: 18px; color: #F6B805;"></i>
            </a>
        </div>
        <div class="row mt-3">
            @foreach($drinkProducts as $drink)
                <div class="col-md-3 mb-2 btn additional-product" data-discount="{{ $drink->diskon }}" data-id="{{ $drink->id }}">
                    <div style="border-radius: 20px;border: 1px solid rgba(0, 0, 0, 0.24);background: #FFF;">
                        <div class="d-flex justify-content-center" style="height: 240px">
                            <img class="img-fluid" src="{{ asset('uploads/Products/'.$drink->gambar) }}" width="240px" style="height: 100%;" alt="{{ $drink->nama_barang }}">
                        </div>
                        <p class="w-100 product-name" style="color: #1e1e1e;text-align: center;font-size: 20px;font-weight: 400;">{{ $drink->nama_barang }}</p>
                        @if($drink->diskon > 0)
                            <div class="d-flex gap-2 justify-content-center">
                                <span class="outer">
                                    <span class="inner">Rp {{ number_format($drink->harga, 0, ',', '.') }}</span>
                                </span>
                                <span class="product-price" style="color: #F6B805;text-align: center;font-size: 20px;font-weight: 400;">Rp {{ number_format($drink->harga - $drink->diskon, 0, ',', '.') }}</span>
                            </div>
                        @else
                            <span class="product-price d-flex justify-content-center" style="color: #F6B805;text-align: center;font-size: 20px;font-weight: 400;">Rp {{ number_format($drink->harga, 0, ',', '.') }}</span>
                        @endif
                        <div class="btn w-100 mt-4 p-3" style="border-radius: 0px 0px 20px 20px;border: 1px solid rgba(0, 0, 0, 0.24);background: #F6B805;">
                            <div class="d-flex gap-2 justify-content-center" style="color: #fff">
                                <i class="bi bi-cart-plus-fill"></i>
                                <span style="color: #FFF;text-align: center;font-size: 18px;font-weight: 400;">Add to Cart</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
    </div>

    @push('js')
        <script>
            let lastRandomProduct = null;
            let currentProductId = null;

            function getRandomProduct() {
                fetch('/get-random-product')
                    .then(response => response.json())
                    .then(data => {
                        const newRandomProduct = data.randomProduct;

                        if (!areProductsEqual(lastRandomProduct, newRandomProduct)) {
                            fadeOutProduct(() => {
                                lastRandomProduct = newRandomProduct;
                                currentProductId = lastRandomProduct.id;
                                updateProduct(lastRandomProduct);
                            });
                        } else {
                            getRandomProduct();
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }

            function areProductsEqual(product1, product2) {
                return (
                    product1 &&
                    product2 &&
                    product1.gambar === product2.gambar &&
                    product1.nama_barang === product2.nama_barang
                );
            }

            document.addEventListener('DOMContentLoaded', function () {
                const pesanSekarangButton = document.getElementById('pesanSekarangButton');
                const checkoutCard = document.getElementById('checkoutCard');

                if (pesanSekarangButton) {
                    pesanSekarangButton.addEventListener('click', function (event) {
                        event.stopPropagation();
                        
                        checkoutCard.classList.add('slide-up');
                        checkoutCard.classList.remove('slide-down');

                    });
                }

                document.body.addEventListener('click', function (event) {
                    if (!checkoutCard.contains(event.target)) {
                        checkCartItems()
                        checkoutCard.classList.remove('slide-up');
                        checkoutCard.classList.add('slide-down');
                        checkoutCard.addEventListener('animationend', function () {
                            checkoutCard.classList.add('d-none');
                            checkoutCard.classList.remove('slide-down');
                        }, { once: true });
                    }
                });
            });

            function pesanSekarangClicked() {
                const checkoutImage = document.getElementById('checkoutImage');
                const checkoutProductName = document.getElementById('checkoutProductName');
                const checkoutProductPrice = document.getElementById('checkoutProductPrice');
                const checkoutCard = document.getElementById('checkoutCard');
                const checkoutID = document.getElementById('checkoutID');

                let quantityInput = document.getElementById("quantity");
                quantityInput.value = 1;

                if(lastRandomProduct.harga !== null){
                    checkoutCard.classList.remove('d-none')
                }
                
                var harga = lastRandomProduct.harga - lastRandomProduct.diskon;
                var hargaRupiah = formatRupiah(harga);

                checkoutProductName.innerHTML = lastRandomProduct.nama_barang;
                checkoutProductPrice.innerHTML = "Rp " + hargaRupiah;
                checkoutID.value = currentProductId;

                if (checkoutImage) {
                    checkoutImage.src = "{{ asset('uploads/Products/') }}" + '/' + lastRandomProduct.gambar;
                    checkoutImage.alt = lastRandomProduct.nama_barang;
                }
            }

            document.addEventListener('DOMContentLoaded', function () {
                const pesanSekarangButton = document.getElementById('pesanSekarangButton');
                if (pesanSekarangButton) {
                    pesanSekarangButton.addEventListener('click', pesanSekarangClicked);
                }
            });

            function updateCheckoutCard(product) {
                const checkoutImage = document.getElementById('checkoutImage');
                const checkoutProductName = document.getElementById('checkoutProductName');
                const checkoutProductPrice = document.getElementById('checkoutProductPrice');
                const checkoutCard = document.getElementById('checkoutCard');
                const checkoutID = document.getElementById('checkoutID');
                let quantityInput = document.getElementById("quantity");
                quantityInput.value = 1;

                checkoutCard.classList.add('slide-up');
                checkoutCard.classList.remove('d-none');

                var harga = product.harga - product.diskon;
                var hargaRupiah = formatRupiah(harga);

                checkoutProductName.innerHTML = product.nama_barang;
                checkoutProductPrice.innerHTML = "Rp " + hargaRupiah;
                checkoutID.value = product.id;

                if (checkoutImage) {
                    checkoutImage.src = product.gambar;
                    checkoutImage.alt = product.nama_barang;
                }
            }

            document.addEventListener('DOMContentLoaded', function () {
                const productElements = document.querySelectorAll('.additional-product');

                productElements.forEach(function (element) {
                    element.addEventListener('click', function () {
                        event.stopPropagation();
                        const idBarang = element.dataset.id;
                        const productName = element.querySelector('.product-name').innerText;
                        const productPrice = element.querySelector('.product-price').innerText;

                        const discount = parseFloat(element.dataset.discount) || 0;
                        const imageSource = element.querySelector('.img-fluid').src;

                        const clickedProduct = {
                            id : idBarang,
                            nama_barang: productName,
                            harga: parseFloat(productPrice.replace(/[^\d]/g, '')),
                            diskon: 0,
                            gambar: imageSource,
                        };

                        updateCheckoutCard(clickedProduct);
                    });
                });
            });

            function fadeOutProduct(callback) {
                const productImage = document.getElementById('productImage');
                const twoLetters = document.getElementById('twoLetters');
                const productName = document.getElementById('productName');

                productImage.classList.add('fade-out');
                twoLetters.classList.add('fade-out');
                productName.classList.add('fade-out');

                setTimeout(() => {
                    productImage.classList.remove('fade-out');
                    twoLetters.classList.remove('fade-out');
                    productName.classList.remove('fade-out');
                    callback();
                }, 500);
            }
        
            function updateProduct(product) {
                const productImage = document.getElementById('productImage');
                const twoLetters = document.getElementById('twoLetters');
                const productName = document.getElementById('productName');

                productImage.classList.add('fade-in');
                twoLetters.classList.add('fade-in');
                productName.classList.add('fade-in');

                productImage.src = "{{ asset('uploads/Products/') }}" + '/' + product.gambar;
                productImage.alt = product.nama_barang;
                twoLetters.innerHTML = product.nama_barang.substring(0, 2);
                productName.innerHTML = product.nama_barang.substring(2);

                setTimeout(() => {
                    productImage.classList.remove('fade-in');
                    twoLetters.classList.remove('fade-in');
                    productName.classList.remove('fade-in');
                }, 1000);
            }
        
            getRandomProduct();
            setInterval(getRandomProduct, 6000);
        </script>
    @endpush
@endsection