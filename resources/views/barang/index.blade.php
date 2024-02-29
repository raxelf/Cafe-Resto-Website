@extends('layout.app')

@section('title', 'Data Barang')

@section('content')
    <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="padding: 40px">
                <div class="modal-body" style="color: #1e1e1e;text-align: center;font-size: 16px;font-weight: 400;">
                    Apakah anda yakin ingin menghapus data ini?
                </div>

                <div class="d-flex justify-content-center gap-5 mt-4">
                    <button type="button" class="btn w-100" style="width: 25%;border-radius: 20px;border: 1px solid #F6B805; color:#F6B805;" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn w-100" style="width: 25%;color: #fff;border-radius: 20px;background: #F6B805;" id="deleteButton">Ya</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container mb-5">
        <div class="alert position-fixed" style="display: none;background: #FFF;box-shadow: 0px 0px 12px 0px rgba(0, 0, 0, 0.25);top: 2%; right: 2%; z-index: 999" role="alert" id="successAlert">
            <div class="d-flex gap-3 justify-content-start align-items-center">
                <i class="bi bi-check-circle-fill fs-5" style="color:#04BC00;"></i> 
                <span style="color: #1e1e1e;text-align: center;font-size: 16px;font-weight: 400;">
                    Data Barang berhasil dihapus
                </span>
            </div>
        </div>

        <h4 style="margin-top:70px;color: #1e1e1e;font-size: 28px;font-weight: 400;">Data Barang</h4>

        <div class="d-flex flex-column flex-md-row justify-content-between">
            <div class="mb-3 mb-md-0" style="width: 100%; max-width: 240px;">
                <a href="/admin/data/barang/create" class="d-flex align-items-center w-100 btn mt-4 w-100" style="height: 50px;border-radius: 20px;background: #FFF;box-shadow: 0px 0px 8px 0px rgba(0, 0, 0, 0.25);">
                    <span class="w-100 d-flex gap-2 text-center fs-6 justify-content-center" style="color: #1e1e1e;font-weight: 400;">
                        <i class="bi bi-plus-lg d-flex align-items-center"></i>
                        Tambah Barang
                    </span>
                </a>
            </div>
            <div style="width: 100%; max-width: 300px;">
                <div class="mt-md-4 d-flex align-items-center justify-content-start p-3" style="width: 100%;height: 50px;border-radius: 20px;background: #FFF;box-shadow: 0px 0px 8px 0px rgba(0, 0, 0, 0.25);">
                    <span class="w-100 d-flex gap-3" style="font-size: 18px;font-weight: 400;">
                        <i class="bi bi-search"></i>
                        <input class="w-100" type="text" placeholder="Search Barang..." style="outline:none;border:none;font-size: 18px;font-weight: 400;">
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
                            <th>Kategori</th>
                            <th>Nama Barang</th>
                            <th>Harga</th>
                            <th>SKU</th>
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

    @push('js')
        <script>
            function loadPage(page, searchQuery = '') {
                let searchTimer;
                const token = localStorage.getItem('token');

                clearTimeout(searchTimer);
                searchTimer = setTimeout(function () {
                    $.ajax({
                        url: '/api/products?page=' + page + '&search=' + searchQuery,
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
                                    <td colspan="6" style="text-align: center; color: #1e1e1e; font-size: 18px; font-weight: 400;">
                                        No products found.
                                    </td>
                                </tr>`;
                            } else {
                                data.map(function (val, index) {
                                    const formattedHarga = new Intl.NumberFormat('id-ID', {
                                        style: 'currency',
                                        currency: 'IDR',
                                        minimumFractionDigits: 0,
                                        maximumFractionDigits: 0
                                    }).format(val.harga - val.diskon);

                                    if(val.category){
                                        row += `
                                            <tr style="border-bottom: 1px solid rgba(30, 30, 30, 0.25);">
                                                <td style="color: #1e1e1e;font-size: 18px;font-weight: 400;">${(page - 1) * per_page + index + 1}</td>
                                                <td style="color: #1e1e1e;font-size: 18px;font-weight: 400;">${val.category.nama_kategori}</td>
                                                <td style="color: #1e1e1e;font-size: 18px;font-weight: 400;">${val.nama_barang}</td>
                                                <td style="color: #1e1e1e;font-size: 18px;font-weight: 400;">${formattedHarga}</td>
                                                <td style="color: #1e1e1e;font-size: 18px;font-weight: 400;">${val.sku}</td>
                                                <td class="d-flex justify-content-center">
                                                    <a href="/admin/data/barang/edit/${val.id}" class="btn fs-4">
                                                        <i class="bi bi-pencil" style="color: #FDB626;"></i>
                                                    </a>
                                                    <a data-id="${val.id}" class="btn btn-hapus fs-4">
                                                        <i class="bi bi-trash" style="color: #FF0000;"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        `;
                                    } else{
                                        row += `
                                            <tr style="border-bottom: 1px solid rgba(30, 30, 30, 0.25);">
                                                <td style="color: #1e1e1e;font-size: 18px;font-weight: 400;">${(page - 1) * per_page + index + 1}</td>
                                                <td style="color: rgba(30, 30, 30, 0.25);font-size: 18px;font-weight: 400;">Kategori tidak ditemukan.</td>
                                                <td style="color: #1e1e1e;font-size: 18px;font-weight: 400;">${val.nama_barang}</td>
                                                <td style="color: #1e1e1e;font-size: 18px;font-weight: 400;">${formattedHarga}</td>
                                                <td style="color: #1e1e1e;font-size: 18px;font-weight: 400;">${val.sku}</td>
                                                <td class="d-flex justify-content-center">
                                                    <a href="/admin/data/barang/edit/${val.id}" class="btn fs-4">
                                                        <i class="bi bi-pencil" style="color: #FDB626;"></i>
                                                    </a>
                                                    <a data-id="${val.id}" class="btn btn-hapus fs-4">
                                                        <i class="bi bi-trash" style="color: #FF0000;"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        `;
                                    }
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
                }, 300);
            }

            $(function(){
                const token = localStorage.getItem('token');
                
                $.ajax({
                    url : '/api/products',
                    headers : {
                        "Authorization" : "Bearer " + token
                    },
                    success : function ({ data, current_page, per_page, total, last_page }) {
                        let row;

                        if (data.length === 0) {
                            row = `
                                <tr>
                                    <td colspan="6" style="text-align: center; color: #1e1e1e; font-size: 18px; font-weight: 400;">
                                        No products found.
                                    </td>
                                </tr>
                            `;
                        } else {
                            data.map(function (val, index) {
                                const formattedHarga = new Intl.NumberFormat('id-ID', {
                                    style: 'currency',
                                    currency: 'IDR',
                                    minimumFractionDigits: 0,
                                    maximumFractionDigits: 0
                                }).format(val.harga - val.diskon);

                                if(val.category){
                                    row += `
                                        <tr style="border-bottom: 1px solid rgba(30, 30, 30, 0.25);">
                                            <td style="color: #1e1e1e;font-size: 18px;font-weight: 400;">${index + 1}</td>
                                            <td style="color: #1e1e1e;font-size: 18px;font-weight: 400;">${val.category.nama_kategori}</td>
                                            <td style="color: #1e1e1e;font-size: 18px;font-weight: 400;">${val.nama_barang}</td>
                                            <td style="color: #1e1e1e;font-size: 18px;font-weight: 400;">${formattedHarga}</td>
                                            <td style="color: #1e1e1e;font-size: 18px;font-weight: 400;">${val.sku}</td>
                                            <td class="d-flex justify-content-center">
                                                <a href="/admin/data/barang/edit/${val.id}" class="btn fs-4">
                                                    <i class="bi bi-pencil" style="color: #FDB626;"></i>
                                                </a>
                                                <a data-id="${val.id}" class="btn btn-hapus fs-4">
                                                    <i class="bi bi-trash" style="color: #FF0000;"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    `;
                                } else{
                                    row += `
                                        <tr style="border-bottom: 1px solid rgba(30, 30, 30, 0.25);">
                                            <td style="color: #1e1e1e;font-size: 18px;font-weight: 400;">${index + 1}</td>
                                            <td style="color: rgba(30, 30, 30, 0.25);font-size: 18px;font-weight: 400;">Kategori tidak ditemukan.</td>
                                            <td style="color: #1e1e1e;font-size: 18px;font-weight: 400;">${val.nama_barang}</td>
                                            <td style="color: #1e1e1e;font-size: 18px;font-weight: 400;">${formattedHarga}</td>
                                            <td style="color: #1e1e1e;font-size: 18px;font-weight: 400;">${val.sku}</td>
                                            <td class="d-flex justify-content-center">
                                                <a href="/admin/data/barang/edit/${val.id}" class="btn fs-4">
                                                    <i class="bi bi-pencil" style="color: #FDB626;"></i>
                                                </a>
                                                <a data-id="${val.id}" class="btn btn-hapus fs-4">
                                                    <i class="bi bi-trash" style="color: #FF0000;"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    `;
                                }
                            });
                        }

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
                })

                $(document).on('click', '.btn-hapus', function(){
                    const id = $(this).data('id');
                    $('#confirmationModal').modal('show');

                    $('#deleteButton').data('id', id);
                });

                $(document).on('click', '#deleteButton', function(){
                    const id = $(this).data('id');
                    $.ajax({
                        url : '/api/products/' + id,
                        type : "DELETE",
                        headers : {
                            "Authorization" : "Bearer " + token
                        },
                        success : function(data){
                            if(data.message == 'success'){
                                $('#successAlert').fadeIn().delay(500).fadeOut();

                                setTimeout(function(){
                                    location.reload();
                                }, 500);
                            }
                        }
                    });

                    $('#confirmationModal').modal('hide');
                });

                $('input[type="text"]').on('input', function() {
                    const searchQuery = $(this).val();
                    loadPage(1, searchQuery);
                });
            });
        </script>
    @endpush
@endsection