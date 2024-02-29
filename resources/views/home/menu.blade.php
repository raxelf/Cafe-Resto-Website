@extends('layout.home')

@section('title', 'OPPA BOX - Menu')

@section('content')
    <div class="p-4">
        <h4 style="color: #F6B805;font-size: 20px;font-weight: 400;">Menu</h4>
    </div>

    <div class="row p-4">
        <div class="col-md-3">
            <nav class="mt-md-4" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/" style="text-decoration: none;color: rgba(30, 30, 30, 0.33);font-size: 20px;font-weight: 400;">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page" style="color: #F6B805;font-size: 20px;font-weight: 400;">Menu</li>
                </ol>
            </nav>

            <div class="mt-5">
                <p style="color: #1E1E1E;font-size: 24px;font-weight: 700;">Filters</p>
                <div class="d-flex flex-column">
                    @foreach($categories as $category)
                        <button class="btn category-button d-flex gap-3 py-4 mb-3 d-flex justify-content-center @if($category->nama_kategori == 'Foods') activeFilter @endif" data-category="{{ $category->id }}" style="border-radius: 20px;background: #FFF;box-shadow: 0px 0px 12px 0px rgba(0, 0, 0, 0.25);color: #F6B805;font-size: 20px;font-weight: 400;">
                            <i class="bi bi-tag-fill"></i> {{ $category->nama_kategori }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div style="width: 100%">
                <div class="mt-md-4 d-flex align-items-center justify-content-start p-3" style="width: 100%;height: 60px;border-radius: 20px;background: #FFF;box-shadow: 0px 0px 8px 0px rgba(0, 0, 0, 0.25);">
                    <span class="w-100 d-flex gap-3" style="font-size: 18px;font-weight: 400;">
                        <i class="bi bi-search"></i>
                        <input class="w-100" type="text" id="searchInput" placeholder="Search..." style="outline:none;border:none;font-size: 18px;font-weight: 400;">
                    </span>
                </div>                

                <div class="row mt-4">
                    @foreach($products as $product)
                        <a class="additional-product col-md-4 col-lg-3 mb-4 btn" data-category="{{ $product->id_kategori }}" data-id="{{ $product->id }}">
                            <div class="h-100">
                                <div style="border-radius: 20px; border: 1px solid rgba(0, 0, 0, 0.24); background: #FFF; height: 100%; display: flex; flex-direction: column;">
                                    <div style="height: 220px; overflow: hidden; border-radius: 20px 20px 0 0;">
                                        <img class="img-fluid" src="{{ asset('uploads/Products/'.$product->gambar) }}" style="width: 100%; object-fit: cover;" alt="{{ $product->nama_barang }}">
                                    </div>
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <p class="product-name card-text text-center mb-3" style="color: #1e1e1e; font-size: 20px; font-weight: 400;">{{ $product->nama_barang }}</p>
                                        @if($product->diskon > 0)
                                            <div class="d-flex gap-2 justify-content-center mb-3">
                                                <span class="outer">
                                                    <span class="inner">Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
                                                </span>
                                                <span class="product-price" style="color: #F6B805; text-align: center; font-size: 20px; font-weight: 400;">Rp {{ number_format($product->harga - $product->diskon, 0, ',', '.') }}</span>
                                            </div>
                                        @else
                                            <div class="text-center mb-3">
                                                <span class="product-price" style="color: #F6B805; text-align: center; font-size: 20px; font-weight: 400;">Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="btn w-100 p-3" style="border-radius: 0 0 20px 20px; border: 1px solid rgba(0, 0, 0, 0.24); background: #F6B805;">
                                        <div class="d-flex gap-2 justify-content-center" style="color: #fff;">
                                            <i class="bi bi-cart-plus-fill"></i>
                                            <span style="color: #FFF; text-align: center; font-size: 18px; font-weight: 400;">Add to Cart</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script>
            $(document).ready(function() {
                var defaultCategory = {{ $defaultCategoryId ?? 'null' }};

                var urlParams = new URLSearchParams(window.location.search);
                var namaKategori = urlParams.get('category');

                function filterProducts(categoryId) {
                    $('.category-button').removeClass('activeFilter');
                    $('.category-button[data-category="' + categoryId + '"]').addClass('activeFilter');
                    $('.btn[data-category]').hide();
                    $('.btn[data-category="' + categoryId + '"]').show();
                }

                function getCategoryIDByName(namaKategori, callback) {
                    $.ajax({
                        url: '/api/getCategoryId',
                        method: 'GET',
                        data: { nama_kategori: namaKategori },
                        success: function(response) {
                            callback(response.categoryId);
                        },
                        error: function() {
                            console.error('Error fetching category ID');
                        }
                    });
                }

                if (defaultCategory !== null) {
                    filterProducts(defaultCategory);
                }

                if (namaKategori !== null) {
                    getCategoryIDByName(namaKategori, function(categoryId) {
                        filterProducts(categoryId);
                    });
                }

                $('.category-button').click(function() {
                    var categoryId = $(this).data('category');
                    filterProducts(categoryId);
                });

                $('#searchInput').on('input', function() {
                    var searchTerm = $(this).val().toLowerCase();

                    $('.btn[data-category]').hide();
                    $('.btn:contains("' + searchTerm + '")').show();
                });
            });

            jQuery.expr[':'].contains = function(a, i, m) {
                return jQuery(a).text().toLowerCase().indexOf(m[3].toLowerCase()) >= 0;
            };

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

                const checkoutCard = document.getElementById('checkoutCard');
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
        </script>
    @endpush
@endsection