<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>OPPA BOX - Order Details</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">

    <style>
        #map { height: 360px; }
    </style>
</head>

<body>
    <div class="container mt-5 mb-5">
        <a href="/" class="btn">
            <h4 class="d-flex gap-3 align-items-center" style="color: #1e1e1e; font-size: 28px; font-weight: 400;">
                <i class="bi bi-arrow-left"></i>
                Order Details
            </h4>
        </a>

        <div class="row mt-3">
            <div class="col-md-2">
                <div class="btn d-flex gap-3 align-items-center py-3 justify-content-center" data-bs-toggle="modal" data-bs-target="#alamatModal"
                    style="border-radius: 20px; background: #FFF; box-shadow: 0px 0px 12px 0px rgba(0, 0, 0, 0.25);">
                    <i class="bi bi-bookmark-fill fs-5" style="color: #F6B805"></i>
                    <span style="color: #1e1e1e; font-size: 18px; font-weight: 400;">
                        Alamat
                    </span>
                </div>
            </div>
            <div class="col-md-8">
                <div class="d-flex flex-column mt-2" style="color: rgba(30, 30, 30, 0.25);font-size: 14px;font-weight: 400;">
                    <span id="namaNowhatsapp"></span>
                    <span id="alamatText"></span>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            @if(count($cartItems) > 0)
                <p style="color: #1e1e1e;font-size: 18px;font-weight: 400;">Metode Pembayaran</p>
        
                <div class="col-md-2 mb-2">
                    <div class="d-flex gap-3 align-items-center justify-content-center py-2 btn payment-option h-100"
                        data-payment-method="cash"
                        style="border-radius: 20px;background: #FFF;box-shadow: 0px 0px 12px 0px rgba(0, 0, 0, 0.25);">
                        <i class="bi bi-cash fs-3"></i>
                        <span style="color: #1E1E1E;font-size: 18px;font-weight: 600;">Cash</span>
                    </div>
                </div>
                <div class="col-md-2 mb-2">
                    <div class="d-flex gap-3 align-items-center justify-content-center py-2 btn payment-option h-100"
                        data-payment-method="QRIS"
                        style="border-radius: 20px;background: #FFF;box-shadow: 0px 0px 12px 0px rgba(0, 0, 0, 0.25);">
                        <i class="bi bi-qr-code-scan fs-3"></i>
                        <span style="color: #1E1E1E;font-size: 18px;font-weight: 600;">QRIS</span>
                    </div>
                </div>
                <div class="col-md-2 mb-2">
                    <div class="d-flex gap-3 align-items-center justify-content-center py-2 btn payment-option h-100"
                        data-payment-method="DANA"
                        style="border-radius: 20px;background: #FFF;box-shadow: 0px 0px 12px 0px rgba(0, 0, 0, 0.25);">
                        <img src="{{ asset('img/dana-logo.webp') }}" alt="dana" width="30px" style="border-radius: 20px">
                        <span style="color: #1E1E1E;font-size: 18px;font-weight: 600;">DANA</span>
                    </div>
                </div>
                <div class="col-md-2 mb-2">
                    <div class="d-flex gap-3 align-items-center justify-content-center py-2 btn payment-option h-100"
                        data-payment-method="SHOPEEPAY"
                        style="border-radius: 20px;background: #FFF;box-shadow: 0px 0px 12px 0px rgba(0, 0, 0, 0.25);">
                        <img src="{{ asset('img/spay-logo.webp') }}" alt="shopeepay" width="30px" >
                        <span style="color: #1E1E1E;font-size: 18px;font-weight: 600;">ShopeePay</span>
                    </div>
                </div>
                <div class="col-md-2 mb-2">
                    <div class="d-flex gap-3 align-items-center justify-content-center py-2 btn payment-option h-100"
                        data-payment-method="BRI"
                        style="border-radius: 20px;background: #FFF;box-shadow: 0px 0px 12px 0px rgba(0, 0, 0, 0.25);">
                        <img src="{{ asset('img/logo_bank_bri.webp') }}" alt="bri" width="40px" >
                        <span style="color: #1E1E1E;font-size: 18px;font-weight: 600;">BRI</span>
                    </div>
                </div>
                <div class="col-md-2 mb-2">
                    <div class="d-flex gap-3 align-items-center justify-content-center py-2 btn payment-option h-100"
                        data-payment-method="BNI"
                        style="border-radius: 20px;background: #FFF;box-shadow: 0px 0px 12px 0px rgba(0, 0, 0, 0.25);">
                        <img src="{{ asset('img/bni-logo.webp') }}" alt="bni" width="30px" >
                        <span style="color: #1E1E1E;font-size: 18px;font-weight: 600;">BNI</span>
                    </div>
                </div>
            @endif
        </div>

        <div class="mt-4">
            @if(count($cartItems) > 0)
                @foreach ($cartItems as $item)
                    <div class="row justify-content-between mb-2 align-items-center" style="border-bottom: 1px solid rgba(30, 30, 30, 0.25);" data-item-id="{{ $item->id }}">
                        <div class="col-md-6 col-lg-8">
                            <div class="d-flex gap-3 align-items-center">
                                <img src="{{ asset('uploads/Products/') . '/' . $item->product->gambar }}" alt="{{ $item->product->name }}" class="img-fluid" width="120px">
                                <span style="color: #1e1e1e; font-size: 18px; font-weight: 400;">{{ $item->product->nama_barang }}</span>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                            <div class="d-flex flex-column align-items-end">
                                <div class="d-flex justify-content-center mb-2">
                                    <span class="total-price" data-price="{{ $item->product->harga - $item->product->diskon }}" style="color: #1E1E1E;font-size: 20px;font-weight: 400;">
                                        Rp {{ number_format(($item->product->harga - $item->product->diskon) * $item->quantity, 0, ',', '.') }}
                                    </span>
                                </div>
                    
                                <div class="input-group d-flex justify-content-end align-items-center gap-3">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-sm btn-minus">
                                            <i class="bi bi-dash-circle fs-6" style="color: #F6B805"></i>
                                        </button>
                                    </span>
                                    <span class="quantity" style="color: #1e1e1e;font-size: 16px;font-weight: 400;">{{ $item->quantity }}</span>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-sm btn-plus">
                                            <i class="bi bi-plus-circle fs-6" style="color: #F6B805"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <p>Your cart is empty. <a href="/">Return to Home</a></p>
            @endif
        </div>

        <div class="mt-3 d-flex flex-column justify-content-end align-items-end">
            @if(count($cartItems) > 0)
                <div class="d-flex gap-3">
                    <span style="color: rgba(30, 30, 30, 0.33);font-size: 18px;font-weight: 400;">Subtotal</span>
                    <span id="subtotal" class="subtotal-amount" style="color: #1E1E1E;font-size: 18px;font-weight: 400;">Free</span>
                </div>
                <div class="d-flex gap-3">
                    <span style="color: rgba(30, 30, 30, 0.33);font-size: 18px;font-weight: 400;">Ongkir</span>
                    <span class="ongkir" style="color: #1E1E1E;font-size: 18px;font-weight: 400;">0</span>
                </div>
                <div class="d-flex gap-3">
                    <span style="color: rgba(30, 30, 30, 0.33);font-size: 18px;font-weight: 400;">Biaya Lainnya</span>
                    <span id="biaya-lain" class="biaya-lain" style="color: #1E1E1E;font-size: 18px;font-weight: 400;">0</span>
                </div>
                <hr class="mt-4" style="width: 20%; margin-top: 5px; margin-bottom: 5px;">

                <div class="d-flex gap-3 align-items-center">
                    <span style="color: #1e1e1e;font-size: 18px;font-weight: 400;">Total</span>
                    <span class="total-bayar" style="color: #F6B805;font-size: 20px;font-weight: 700;">Free</span>
                </div>

                <button id="orderNowButton" class="mt-4 btn px-4 py-1 d-flex gap-2 align-items-center mb-2" style="border-radius:20px ;background: #F6B805;text-align: center;font-size: 18px;font-weight: 600;">
                    <i class="bi bi-basket2-fill fs-3 fs-md-4" style="color: #fff"></i>
                    <span style="color: #fff; font-size: 18px; font-weight: 600;">Order Now</span>
                </button>
            @endif
            
        </div>

        {{-- Modal Alamat --}}
        <div class="modal modal-lg fade" id="alamatModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="d-flex justify-content-between">
                            <h4 data-bs-dismiss="modal" class="btn" style="color: #1e1e1e;font-size: 24px;font-weight: 400;"><i class="bi bi-arrow-left"></i> Alamat</h4>
                            <button type="button" class="mb-3 btn d-flex align-items-center gap-3" id="clearAddressButton" style="border-radius: 20px; background: #FFF; box-shadow: 0px 0px 12px 0px rgba(0, 0, 0, 0.25);">
                                <i class="bi bi-x-circle"></i>
                                Clear
                            </button>
                        </div>
                        <form id="formAlamat">
                            <div class="input-group mb-3">
                                <div class="form-floating">
                                  <input type="text" class="form-control" id="nama" placeholder="Nama" name="nama">
                                  <label for="nama">Nama</label>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <div class="form-floating">
                                  <input type="text" class="form-control" id="nowhatsapp" name="nowhatsapp" placeholder="No Whatsapp">
                                  <label for="nowhatsapp">No Whatsapp</label>
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="selectedAddress" placeholder="Pilih Alamat di Peta" name="selectedAddress" readonly>
                                <label for="selectedAddress">Pilih Alamat di Peta</label>
                            </div>
                            <div class="input-group mb-3">
                                <div class="form-floating">
                                  <input type="text" class="form-control" id="detailalamat" name="detailalamat" placeholder="Nama Tempat / Blok / Warna Rumah">
                                  <label for="detailalamat">Nama Tempat / Blok / Warna Rumah</label>
                                </div>
                            </div>
                            <div id="map" class="mb-3"></div>

                            <div class="d-flex justify-content-end mb-3">
                                <button type="submit" class="btn d-flex gap-3 justify-content-center align-items-center" style="border-radius:18px ;background: #F6B805;color: #FFF;text-align: center;font-size: 20px;font-weight: 600;">
                                    <i class="bi bi-check-lg fs-6"></i>
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Confirm --}}
        <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content" style="padding: 40px">
                    <div class="modal-body" style="color: #1e1e1e;text-align: center;font-size: 16px;font-weight: 400;">
                        Apakah anda yakin ingin memesan? <br>
                        Silahkan Cek Kembali sebelum memesan.
                    </div>
    
                    <div class="d-flex justify-content-center gap-5 mt-4">
                        <button type="button" class="btn w-100" style="width: 25%;border-radius: 20px;border: 1px solid #F6B805; color:#F6B805;" data-bs-dismiss="modal" data-bs-target="#confirmationModal">Batal</button>
                        <button type="button" class="btn w-100" style="width: 25%;color: #fff;border-radius: 20px;background: #F6B805;" id="orderButton">Ya</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            updateSubtotal();

            $(".btn-plus").on("click", function () {
                var itemId = $(this).closest(".row").data("item-id");
                updateQuantity($(this), 1, itemId);
            });

            $(".btn-minus").on("click", function () {
                var itemId = $(this).closest(".row").data("item-id");
                updateQuantity($(this), -1, itemId);
            });

            function updateQuantity(button, delta, itemId) {
                var quantityElement = button.closest(".input-group").find(".quantity");
                var currentQuantity = parseInt(quantityElement.text());
                var newQuantity = currentQuantity + delta;
                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                if (newQuantity < 1) {
                    $.ajax({
                        url: '/cart/' + itemId,
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        success: function (response) {
                            quantityElement.text(newQuantity);

                            if (newQuantity === 0) {
                                var rowElement = button.closest(".row");
                                rowElement.remove();
                            }
                            updateTotalPrice(itemId, newQuantity);
                            updateBiayaLain();

                            location.reload();
                        },
                        error: function (error) {
                            console.error("Error deleting item from cart:", error);
                        }
                    });
                } else {
                    if (delta < 0) {
                        decreaseQuantity(itemId);
                    } else {
                        increaseQuantity(itemId);
                    }
                    updateTotalPrice(itemId, newQuantity);
                    updateSubtotal();
                    updateBiayaLain();
                }
            }

            function increaseQuantity(itemId) {
                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: '/cart/addQuantity/' + itemId,
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function (response) {
                        var quantityElement = $(".row[data-item-id='" + itemId + "']").find(".quantity");
                        var currentQuantity = parseInt(quantityElement.text());
                        quantityElement.text(currentQuantity + 1);
                    },
                    error: function (error) {
                        console.error("Error increasing quantity:", error);
                    }
                });
            }

            function decreaseQuantity(itemId) {
                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: '/cart/decreaseQuantity/' + itemId,
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function (response) {
                        var quantityElement = $(".row[data-item-id='" + itemId + "']").find(".quantity");
                        var currentQuantity = parseInt(quantityElement.text());
                        quantityElement.text(currentQuantity - 1);
                    },
                    error: function (error) {
                        console.error("Error decreasing quantity:", error);
                    }
                });
            }

            function updateTotalPrice(itemId, newQuantity) {
                var priceElement = $(".row[data-item-id='" + itemId + "']").find(".total-price");
                var originalPrice = parseFloat(priceElement.attr("data-price"));
                var newTotalPrice = originalPrice * newQuantity;

                priceElement.text(formatCurrency(newTotalPrice));
                updateSubtotal();
            }

            function updateSubtotal() {
                var subtotal = 0;

                $('.total-price').each(function() {
                    var price = parseFloat($(this).text().replace(/[^\d,]/g, ''));

                    subtotal += price;
                });

                $('.subtotal-amount').text(formatCurrency(subtotal));

                var ongkir = parseFloat($('.ongkir').text().replace(/[^\d,]/g, ''));
                var biayaLain = parseFloat($('.biaya-lain').text().replace(/[^\d,]/g, ''));

                var total = subtotal + ongkir + biayaLain;
                $('.total-bayar').text(formatCurrency(total));
            }

            function formatCurrency(amount) {
                var formattedAmount = amount.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' });

                return formattedAmount.replace(/\,00$/, '');
            }

            const paymentOptions = document.querySelectorAll('.payment-option');

            paymentOptions.forEach(option => {
                option.addEventListener('click', function() {
                    paymentOptions.forEach(opt => opt.classList.remove('selectedPayment'));
                    this.classList.add('selectedPayment');

                    updateBiayaLain();
                });
            });

            function updateBiayaLain() {
                var selectedPayment = $(".payment-option.selectedPayment").data("payment-method");
                const subtotal = parseFloat($('.subtotal-amount').text().replace(/[^\d,]/g, ''));

                let biayaLain = 0;
                if (selectedPayment === 'QRIS') {
                    biayaLain = subtotal * 0.02;
                } else if (selectedPayment === 'DANA' || selectedPayment === 'SHOPEEPAY') {
                    biayaLain = subtotal * 0.04;
                } else if (selectedPayment === 'BRI' || selectedPayment === 'BNI'){
                    biayaLain = 5000;
                }
                biayaLain = formatCurrency(biayaLain);

                const biayaLainElement = document.getElementById('biaya-lain');
                biayaLainElement.textContent = biayaLain;

                updateTotalPrice();
            }
            
        });
        
    </script>
    {{-- map --}}
    {{-- Please Insert your Google Map Key--}}
    <script src="https://maps.googleapis.com/maps/api/js?key=[YOURKEY]&libraries=places"></script>

    <script>
        var map;
        var marker;
        
        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                center: { lat: -0.951084546269697, lng: 100.39760716309523 },
                zoom: 13,
                streetViewControl: false
            });

            map.addListener('click', function (event) {
                if (marker) {
                    marker.setMap(null);
                }

                marker = new google.maps.Marker({
                    position: event.latLng,
                    map: map
                });

                reverseGeocode(event.latLng);
            });
        }

        function reverseGeocode(latlng) {
            var geocoder = new google.maps.Geocoder();

            geocoder.geocode({ 'location': latlng }, function (results, status) {
                if (status === 'OK') {
                    if (results[0]) {
                        $('#selectedAddress').val(results[0].formatted_address);
                    } else {
                        console.error('No results found');
                    }
                } else {
                    console.error('Geocoder failed due to: ' + status);
                }
            });
        }

        function geocodeSelectedAddress() {
            var selectedAddress = $("#selectedAddress").val();

            if (selectedAddress) {
                var geocoder = new google.maps.Geocoder();

                geocoder.geocode({ 'address': selectedAddress }, function (results, status) {
                    if (status === 'OK' && results[0]) {

                        if (marker) {
                            marker.setMap(null);
                        }

                        marker = new google.maps.Marker({
                            map: map,
                            position: results[0].geometry.location
                        });

                    } else {
                        console.error('Geocode was not successful for the following reason: ' + status);
                    }
                });
            }
        }

        $('#alamatModal').on('shown.bs.modal', function(){
            setTimeout(function() {
                initMap();
                geocodeSelectedAddress();
            }, 10);
        });

        $(document).ready(function () {
            $("#clearAddressButton").on("click", function () {
                clearAddressData();

                function clearAddressData() {
                    document.cookie = "formData=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";

                    $("#nama").val("");
                    $("#nowhatsapp").val("");
                    $("#selectedAddress").val("");
                    $("#detailalamat").val("");

                    displayDataBesideButton(null);
                }
            });

            loadAndDisplayCookieData();

            $("#formAlamat").submit(function (event) {
                event.preventDefault();
                
                var nama = $("#nama").val().trim();
                var nowhatsapp = $("#nowhatsapp").val().trim();
                var selectedAddress = $("#selectedAddress").val().trim();
                if (!isValidInput(nama, nowhatsapp, selectedAddress)) {
                    return;
                }
                
                function isValidInput(nama, nowhatsapp, selectedAddress) {
                    if (nama === "") {
                        alert("Nama tidak boleh kosong.");
                        return false;
                    }
        
                    if (nowhatsapp === "") {
                        alert("Nomor Whatsapp tidak boleh kosong.");
                        return false;
                    }
        
                    if (selectedAddress === "") {
                        alert("Alamat tidak boleh kosong.");
                        return false;
                    }
        
                    return true;
                }

                var formData = {
                    nama: $("#nama").val(),
                    nowhatsapp: $("#nowhatsapp").val(),
                    selectedAddress: $("#selectedAddress").val(),
                    detailAlamat: $('#detailalamat').val(),
                };

                var formDataJSON = JSON.stringify(formData);

                document.cookie = "formData=" + formDataJSON + "; path=/";

                $("#alamatModal").modal("hide");

                location.reload();
            });

            function loadAndDisplayCookieData() {
                var formDataCookie = getCookie("formData");

                var formData = formDataCookie ? JSON.parse(formDataCookie) : null;

                displayDataBesideButton(formData);

                if (formData && formData.selectedAddress) {
                    geocodeSelectedAddress();
                }
            }

            function getCookie(cookieName) {
                var name = cookieName + "=";
                var decodedCookie = decodeURIComponent(document.cookie);
                var cookieArray = decodedCookie.split(";");

                for (var i = 0; i < cookieArray.length; i++) {
                    var cookie = cookieArray[i].trim();
                    if (cookie.indexOf(name) === 0) {
                        return cookie.substring(name.length, cookie.length);
                    }
                }
                return null;
            }

            function displayDataBesideButton(formData) {
                if (formData) {
                    $("#namaNowhatsapp").text(formData.nama + ' (' + formData.nowhatsapp + ')');
                    $("#alamatText").text(formData.selectedAddress + ' (' + formData.detailAlamat + ')');

                    $("#nama").val(formData.nama);
                    $("#nowhatsapp").val(formData.nowhatsapp);
                    $("#selectedAddress").val(formData.selectedAddress);
                    $("#detailalamat").val(formData.detailAlamat);
                } else {
                    $("#namaNowhatsapp").text("");
                    $("#alamatText").text("");
                    
                    $("#nama").val("");
                    $("#nowhatsapp").val("");
                    $("#selectedAddress").val("");
                    $("#detailalamat").val("");
                }
            }

            const paymentOptions = document.querySelectorAll('.payment-option');

            paymentOptions.forEach(option => {
                option.addEventListener('click', function() {
                    paymentOptions.forEach(opt => opt.classList.remove('selectedPayment'));

                    this.classList.add('selectedPayment');
                });
            });

            $("#orderNowButton").on("click", function () {
                if (!isValidAddress()) {
                    alert("Masukkan Alamat yang valid.");
                    return;
                }

                if (!isValidPaymentMethod()) {
                    alert("Mohon pilih Metode Pembayaran.");
                    return;
                }

                $("#confirmationModal").modal("show");
            });

            function isValidAddress() {
                var nama = $("#nama").val().trim();
                var nowhatsapp = $("#nowhatsapp").val().trim();
                var selectedAddress = $("#selectedAddress").val().trim();
                
                if (nama === "" || nowhatsapp === "" || selectedAddress === "") {
                    return false;
                }

                return true;
            }

            function isValidPaymentMethod() {
                var selectedPayment = $(".payment-option.selectedPayment").data("payment-method");
                return selectedPayment !== undefined;
            }

            $("#orderButton").on("click", function () {
                if (!isValidAddress()) {
                    alert("Please enter a valid address.");
                    return;
                }
    
                if (!isValidPaymentMethod()) {
                    alert("Please select a payment method.");
                    return;
                }
    
                $(this).prop('disabled', true);
                
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: '/place-order',
                    method: 'POST',
                    data: {
                        nama: $("#nama").val(),
                        nowhatsapp: $("#nowhatsapp").val(),
                        selectedAddress: $("#selectedAddress").val(),
                        detailalamat: $('#detailalamat').val(),
                        paymentMethod: $(".payment-option.selectedPayment").data("payment-method"),
                    },
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function (response) {
                        alert(response.message);
                        window.location.href = '/thankyou/' + response.invoice;
                    },
                    error: function (error) {
                        console.error("Error placing order:", error);
                        alert("Error placing order. Please try again.");
                    },
                    complete: function () {
                        $("#orderButton").prop('disabled', false);
                    }
                });
            });
        });
        
    </script>
</body>
</html>
