@extends('layout.app')

@section('title', 'Pesanan')

@section('content')
    <div class="modal fade" id="lihatModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
            <div class="modal-content" style="padding: 40px">
                <div id="isiModal">

                </div>

                <div class="d-flex justify-content-end gap-5 mt-4">
                    <button type="button" class="btn w-100" style="color: #fff;border-radius: 20px;background: #F6B805;" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container mb-5">
        <h4 style="margin-top:70px;color: #1e1e1e;font-size: 28px;font-weight: 400;">Pesanan</h4>

        <div class="d-flex flex-column flex-md-row justify-content-between">
            <div class="mt-4">
                <select class="form-select" id="orderStatus" onchange="filterOrders()" style="height: 50px;border-radius: 20px;box-shadow: 0px 0px 8px 0px rgba(0, 0, 0, 0.25);">
                    <option value="all">Semua Pesanan</option>
                    <option value="baru">Pesanan Baru</option>
                    <option value="dikemas">Pesanan Dikemas</option>
                    <option value="diantar">Pesanan Diantar</option>
                    <option value="selesai">Pesanan Selesai</option>
                </select>
            </div>
            <div style="width: 100%; max-width: 300px;">
                <div class="mt-4 d-flex align-items-center justify-content-start p-3" style="width: 100%;height: 50px;border-radius: 20px;background: #FFF;box-shadow: 0px 0px 8px 0px rgba(0, 0, 0, 0.25);">
                    <span class="w-100 d-flex gap-3" style="font-size: 18px;font-weight: 400;">
                        <i class="bi bi-search"></i>
                        <input class="w-100" type="text" placeholder="Search Invoices.." style="outline:none;border:none;font-size: 18px;font-weight: 400;">
                    </span>
                </div>
            </div>
        </div>

        <div class="mt-4 h-100">
            <div id="kategori-table" class="table-responsive">
                <table class="w-100">
                    <thead class="text-center">
                        <tr>
                            <th style="border-radius: 20px 0 0 0">No</th>
                            <th>Tanggal</th>
                            <th>Invoice</th>
                            <th>Total</th>
                            <th>Metode Pembayaran</th>
                            <th>Pembayaran</th>
                            <th>Status</th>
                            <th style="border-radius: 0 20px 0 0"></th>
                        </tr>
                    </thead>
                    <tbody style="border: 1px solid #1e1e1e;">
                        
                    </tbody>
                </table>
            </div>
            <div class="w-100" style="border-radius: 0 0 20px 20px;background: #FFF; border-left: 1px solid #1e1e1e;border-right: 1px solid #1e1e1e;border-bottom: 1px solid #1e1e1e;">
                <div class="w-100 d-flex justify-content-center gap-3 p-4 align-items-center pagination">
                    
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-lg fade" id="inspectModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    
                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAmI0Fe8hrtG2vxlCaegaTtp1fRL0rJ7h4&libraries=places"></script>
        <script>
            function loadPage(page, searchQuery = '') {
                const token = localStorage.getItem('token');
                const selectedStatus = $('#orderStatus').val();

                let apiUrl = '/api/orders';

                if (selectedStatus !== 'all') {
                    apiUrl = `/api/pesanan/${selectedStatus}`;
                }

                $.ajax({
                    url: apiUrl + '?page=' + page + '&search=' + searchQuery,
                    headers: {
                        "Authorization": "Bearer " + token
                    },
                    success: function ({ data, current_page, per_page, total, last_page }) {
                        if (page < 1 || page > last_page) {
                            return;
                        }
                        let row;

                        if (data.length === 0) {
                            row = `
                            <tr>
                                <td colspan="8" style="text-align: center; color: #1e1e1e; font-size: 18px; font-weight: 400;">
                                    Belum ada pesanan.
                                </td>
                            </tr>`;
                        } else {
                            data.map(function (val, index) {
                                function formatDateToIndonesianWithSeparateTime(dateString) {
                                    const months = [
                                        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                                        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                                    ];

                                    const date = new Date(dateString);
                                    const day = date.getDate();
                                    const month = months[date.getMonth()];
                                    const year = date.getFullYear();
                                    const hours = String(date.getHours()).padStart(2, '0');
                                    const minutes = String(date.getMinutes()).padStart(2, '0');

                                    const formattedDate = `${day} ${month} ${year}`;
                                    const formattedTime = `${hours}:${minutes}`;

                                    return { formattedDate, formattedTime };
                                }

                                function formatDateAgo(dateString) {
                                    const date = new Date(dateString);
                                    const now = new Date();

                                    const diffInMilliseconds = now - date;
                                    const diffInSeconds = Math.floor(diffInMilliseconds / 1000);
                                    const diffInMinutes = Math.floor(diffInSeconds / 60);
                                    const diffInHours = Math.floor(diffInMinutes / 60);
                                    const diffInDays = Math.floor(diffInHours / 24);

                                    if (diffInDays > 0) {
                                        return `${diffInDays} ${diffInDays > 1 ? 'hari' : 'hari'} lalu`;
                                    } else if (diffInHours > 0) {
                                        return `${diffInHours} ${diffInHours > 1 ? 'jam' : 'jam'} lalu`;
                                    } else if (diffInMinutes > 0) {
                                        return `${diffInMinutes} ${diffInMinutes > 1 ? 'menit' : 'menit'} lalu`;
                                    } else {
                                        return 'Baru Saja';
                                    }
                                }

                                const date = new Date(val.created_at);
                                const now = new Date();
                                const diffInMilliseconds = now - date;
                                const diffInDays = Math.floor(diffInMilliseconds / (1000 * 60 * 60 * 24));

                                let displayDate;
                                if (diffInDays > 0) {
                                    const { formattedDate, formattedTime } = formatDateToIndonesianWithSeparateTime(val.created_at);
                                    displayDate = `
                                        <div style="color: #1e1e1e;font-size: 18px;font-weight: 400;">${formattedDate}</div>
                                        <div style="color: #1e1e1e;font-size: 18px;font-weight: 400;">${formattedTime}</div>
                                    `;
                                } else {
                                    displayDate = formatDateAgo(val.created_at);
                                }

                                const formattedHarga = new Intl.NumberFormat('id-ID', {
                                    style: 'currency',
                                   currency: 'IDR',
                                    minimumFractionDigits: 0,
                                    maximumFractionDigits: 0
                                }).format(val.grand_total);

                                row += `
                                    <tr style="border-bottom: 1px solid rgba(30, 30, 30, 0.25);">
                                        <td style="color: #1e1e1e;font-size: 18px;font-weight: 400;">${(page - 1) * per_page + index + 1}</td>
                                        <td style="color: #1e1e1e;font-size: 18px;font-weight: 400;">${displayDate}</td>
                                        <td style="color: #1e1e1e;font-size: 18px;font-weight: 400;">${val.invoice}</td>
                                        <td style="color: #1e1e1e;font-size: 18px;font-weight: 400;">${formattedHarga}</td>
                                        <td style="color: #1e1e1e;font-size: 18px;font-weight: 400;text-transform:uppercase;">${val.payment_method}</td>
                                        <td style="color: #1e1e1e;font-size: 18px;font-weight: 400;">
                                            <div class="d-flex justify-content-center align-items-center" style="text-transform:uppercase;height:40px;${(val.status_pembayaran === 'paid' || val.status_pembayaran === 'settled') ? 'border: 1px solid #04BC00; color: #04BC00;' : 'border: 1px solid #1e1e1e; color: #1e1e1e;'}">
                                                ${val.status_pembayaran === 'paid' || val.status_pembayaran === 'settled' ? 'PAID' : val.status_pembayaran}
                                            </div>
                                        </td>
                                        <td style="color: #1e1e1e;font-size: 18px;font-weight: 400;">
                                            <div class="d-flex justify-content-center align-items-center" style="text-transform:uppercase;height: 40px;${val.status === 'Selesai' ? 'border: 1px solid #04BC00; color: #04BC00;' : 'border: 1px solid #1e1e1e;'}">
                                                ${val.status}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn inspectPesanan" id="inspectPesanan" data-order-id="${val.id}">
                                                <i class="bi bi-eye fs-4" style="color:#0000FF;"></i>    
                                            </div>
                                        </td>
                                    </tr>
                                `;
                            });
                        }

                        $('tbody').html(row);

                        $('.pagination').html(`
                        <div class="w-100 d-flex justify-content-center gap-3 align-items-center">
                            <button type="button" class="btn" onclick="loadPage(${page - 1}, '${searchQuery}')">
                                <i class="bi bi-chevron-left"></i>
                            </button>
                            <span style="color: #1E1E1E;font-size: 14px;font-weight: 400;">
                                Page ${page} of ${last_page}
                            </span>
                            <button type="button" class="btn" onclick="loadPage(${page + 1}, '${searchQuery}')">
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        </div>`);
                    }
                });
            }

            function filterOrders() {
                const selectedStatus = $('#orderStatus').val();
                const token = localStorage.getItem('token');

                let apiUrl = '/api/orders';

                if (selectedStatus !== 'all') {
                    apiUrl = `/api/pesanan/${selectedStatus}`;
                }

                $.ajax({
                    url: apiUrl,
                    headers: {
                        "Authorization": "Bearer " + token
                    },
                    success: function ({ data, current_page, per_page, total, last_page }) {
                        let row;

                        if (data.length === 0) {
                            row = `
                                <tr>
                                    <td colspan="8" style="text-align: center; color: #1e1e1e; font-size: 18px; font-weight: 400;">
                                        Belum ada pesanan.
                                    </td>
                                </tr>
                            `;
                        } else {
                            data.map(function (val, index) {
                                function formatDateToIndonesianWithSeparateTime(dateString) {
                                    const months = [
                                        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                                        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                                    ];

                                    const date = new Date(dateString);
                                    const day = date.getDate();
                                    const month = months[date.getMonth()];
                                    const year = date.getFullYear();
                                    const hours = String(date.getHours()).padStart(2, '0');
                                    const minutes = String(date.getMinutes()).padStart(2, '0');

                                    const formattedDate = `${day} ${month} ${year}`;
                                    const formattedTime = `${hours}:${minutes}`;

                                    return { formattedDate, formattedTime };
                                }

                                function formatDateAgo(dateString) {
                                    const date = new Date(dateString);
                                    const now = new Date();

                                    const diffInMilliseconds = now - date;
                                    const diffInSeconds = Math.floor(diffInMilliseconds / 1000);
                                    const diffInMinutes = Math.floor(diffInSeconds / 60);
                                    const diffInHours = Math.floor(diffInMinutes / 60);
                                    const diffInDays = Math.floor(diffInHours / 24);

                                    if (diffInDays > 0) {
                                        return `${diffInDays} ${diffInDays > 1 ? 'hari' : 'hari'} lalu`;
                                    } else if (diffInHours > 0) {
                                        return `${diffInHours} ${diffInHours > 1 ? 'jam' : 'jam'} lalu`;
                                    } else if (diffInMinutes > 0) {
                                        return `${diffInMinutes} ${diffInMinutes > 1 ? 'menit' : 'menit'} lalu`;
                                    } else {
                                        return 'Baru Saja';
                                    }
                                }

                                const date = new Date(val.created_at);
                                const now = new Date();
                                const diffInMilliseconds = now - date;
                                const diffInDays = Math.floor(diffInMilliseconds / (1000 * 60 * 60 * 24));

                                let displayDate;
                                if (diffInDays > 0) {
                                    const { formattedDate, formattedTime } = formatDateToIndonesianWithSeparateTime(val.created_at);
                                    displayDate = `
                                        <div style="color: #1e1e1e;font-size: 18px;font-weight: 400;">${formattedDate}</div>
                                        <div style="color: #1e1e1e;font-size: 18px;font-weight: 400;">${formattedTime}</div>
                                    `;
                                } else {
                                    displayDate = formatDateAgo(val.created_at);
                                }
                                const formattedHarga = new Intl.NumberFormat('id-ID', {
                                    style: 'currency',
                                   currency: 'IDR',
                                    minimumFractionDigits: 0,
                                    maximumFractionDigits: 0
                                }).format(val.grand_total);

                                row += `
                                    <tr style="border-bottom: 1px solid rgba(30, 30, 30, 0.25);">
                                        <td style="color: #1e1e1e;font-size: 18px;font-weight: 400;">${index + 1}</td>
                                        <td style="color: #1e1e1e;font-size: 18px;font-weight: 400;">${displayDate}</td>
                                        <td style="color: #1e1e1e;font-size: 18px;font-weight: 400;">${val.invoice}</td>
                                        <td style="color: #1e1e1e;font-size: 18px;font-weight: 400;">${formattedHarga}</td>
                                        <td style="color: #1e1e1e;font-size: 18px;font-weight: 400;text-transform:uppercase;">${val.payment_method}</td>
                                        <td style="color: #1e1e1e;font-size: 18px;font-weight: 400;">
                                            <div class="d-flex justify-content-center align-items-center" style="text-transform:uppercase;height:40px;${(val.status_pembayaran === 'paid' || val.status_pembayaran === 'settled') ? 'border: 1px solid #04BC00; color: #04BC00;' : 'border: 1px solid #1e1e1e; color: #1e1e1e;'}">
                                                ${val.status_pembayaran === 'paid' || val.status_pembayaran === 'settled' ? 'PAID' : val.status_pembayaran}
                                            </div>
                                        </td>
                                        <td style="color: #1e1e1e;font-size: 18px;font-weight: 400;">
                                            <div class="d-flex justify-content-center align-items-center" style="text-transform:uppercase;height: 40px;${val.status === 'Selesai' ? 'border: 1px solid #04BC00; color: #04BC00;' : 'border: 1px solid #1e1e1e;'}">
                                                ${val.status}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn inspectPesanan" id="inspectPesanan" data-order-id="${val.id}">
                                                <i class="bi bi-eye fs-4" style="color:#0000FF;"></i>    
                                            </div>
                                        </td>
                                    </tr>
                                `;
                            }); 
                        }

                        $('tbody').empty();
                        $('tbody').append(row);

                        $('.pagination').html(`
                            <div class="w-100 d-flex justify-content-center gap-3 align-items-center">
                                <button type="button" class="btn" onclick="loadPage(${current_page - 1})">
                                    <i class="bi bi-chevron-left"></i>
                                </button>
                                <span style="color: #1E1E1E;font-size: 14px;font-weight: 400;">
                                    Page ${current_page} of ${last_page}
                                </span>
                                <button type="button" class="btn" onclick="loadPage(${current_page + 1})">
                                    <i class="bi bi-chevron-right"></i>
                                </button>
                        </div>`);
                    }
                });
            }

            function inspectOrder(orderId) {
                const token = localStorage.getItem('token');
                const inspectModalBody = $('#inspectModal .modal-body');

                $.ajax({
                    url: `/api/orders/${orderId}`,
                    headers: {
                        "Authorization": "Bearer " + token
                    },
                    success: function (order) {
                        inspectModalBody.html(`
                            <div class="w-100 p-4">
                                <div class="row justify-content-between align-items-center">
                                    <div class="col-md-6">
                                        <div style="color: #1e1e1e;font-size: 18px;font-weight: 700;">
                                            ORDER <span style="color: rgba(30, 30, 30, 0.25)" id="invoice">#${order.data.invoice}</span> <i class="bi bi-copy btn" onclick="copyInvoice()"></i>
                                        </div>
                                    </div>

                                    <div class="col-md-6 text-md-end mt-3 mt-md-0">
                                        <span class="px-4 py-2 rounded text-dark font-weight-400" style="border: 1px solid #1E1E1E; text-transform: uppercase;">${order.data.status}</span>
                                    </div>
                                </div>
                                <hr>

                                <div>
                                    <div>
                                        <p><strong>Nama:</strong> ${order.data.nama} (${order.data.nowhatsapp})</p>
                                        <p><strong>Total:</strong> ${new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(order.data.grand_total)} - ${order.data.payment_method}</p>
                                        <p><strong>Status Pembayaran:</strong> ${order.data.status_pembayaran}</p>
                                        <label for="statusSelect" class="form-label"><strong>Ubah Status</strong></label>
                                        <form id="ubahStatusForm" class="d-flex align-items-center">
                                            <div class="me-3 flex-grow-1"> 
                                                <select class="form-select" id="statusSelect" name="status">
                                                    <option value="Baru" ${order.data.status === 'Baru' ? 'selected' : ''}>Baru</option>
                                                    <option value="Dikemas" ${order.data.status === 'Dikemas' ? 'selected' : ''}>Dikemas</option>
                                                    <option value="Diantar" ${order.data.status === 'Diantar' ? 'selected' : ''}>Diantar</option>
                                                    <option value="Selesai" ${order.data.status === 'Selesai' ? 'selected' : ''}>Selesai</option>
                                                </select>
                                            </div>
                                            <button type="button" class="btn" onclick="ubahStatus(${order.data.id})" style="border-radius:18px ;background: #F6B805;color:#fff;">
                                                Ubah
                                            </button>
                                        </form>
                                        <h5 class="mt-4"><strong>Order Details</strong></h5>
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Product</th>
                                                    <th>Quantity</th>
                                                    <th>Price</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                ${order.data.order_details.map(orderDetail => `
                                                    <tr>
                                                        <td>${orderDetail.product.nama_barang}</td>
                                                        <td>${orderDetail.jumlah}</td>
                                                        <td>${new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(orderDetail.total)}</td>
                                                    </tr>
                                                `).join('')}
                                            </tbody>
                                        </table>
                                        <h5 class="mt-4"><strong>Alamat</strong></h5>
                                        <p>${order.data.alamat} (${order.data.detail_alamat})</p>
                                        <div id="mapContainer" class="mt-4" style="height: 300px;"></div>
                                        <div class="d-flex justify-content-end">
                                            <button type="button" class="btn mt-4 px-4" data-bs-dismiss="modal" style="border-radius:18px ;background: #F6B805;color:#fff;">
                                                Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `);

                        $('#inspectModal').modal('show');

                        function initMap() {
                            const geocoder = new google.maps.Geocoder();
                            const address = `${order.data.alamat}`;
                            const mapContainer = document.getElementById('mapContainer');

                            geocoder.geocode({ 'address': address }, function (results, status) {
                                if (status === 'OK' && results.length > 0) {
                                    const location = results[0].geometry.location;
                                    const map = new google.maps.Map(mapContainer, {
                                        center: location,
                                        zoom: 15
                                    });

                                    new google.maps.Marker({
                                        position: location,
                                        map: map,
                                        title: 'Order Location'
                                    });
                                } else {
                                    console.error('Geocoding failed:', status);
                                }
                            });
                        }

                        $('#inspectModal').on('shown.bs.modal', function(){
                            setTimeout(function() {
                                initMap();
                            }, 10);
                        });
                    }
                });
            }

            function ubahStatus(orderId) {
                const newStatus = $('#statusSelect').val();
                const token = localStorage.getItem('token');

                $.ajax({
                    url: `/api/pesanan/ubah_status/${orderId}`,
                    method: 'POST',
                    data: { status: newStatus },
                    headers: {
                        "Authorization": "Bearer " + token
                    },
                    success: function (response) {
                        alert("Status Pesanan berhasil di-ubah");
                        location.reload();
                    },
                    error: function (error) {
                        alert("Status Pesanan gagal di-ubah");
                    }
                });
            }

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

            $(document).ready(function () {
                filterOrders();
            });

            $(document).on('click', '.inspectPesanan', function () {
                const orderId = $(this).data('order-id');
                inspectOrder(orderId);
            });

            $(function(){
                $('input[type="text"]').on('input', function() {
                    const searchQuery = $(this).val();
                    loadPage(1, searchQuery);
                });
            });
        </script>
    @endpush
@endsection