@extends('layout.app')

@section('title', 'Tambah Barang')

@section('content')
    <div class="container">
        <div class="alert position-fixed" style="display: none;background: #FFF;box-shadow: 0px 0px 12px 0px rgba(0, 0, 0, 0.25);top: 2%; right: 2%; z-index: 999" role="alert" id="successAlert">
            <div class="d-flex gap-3 justify-content-start align-items-center">
                <i class="bi bi-check-circle-fill fs-5" style="color:#04BC00;"></i> 
                <span style="color: #1e1e1e;text-align: center;font-size: 16px;font-weight: 400;">
                    Barang Baru berhasil ditambah
                </span>
            </div>
        </div>

        <h4 style="margin-top:70px;color: #1e1e1e;font-size: 28px;font-weight: 400;">Tambah Barang</h4>

        <form class="form-barang">
            <div class="mb-3 mt-4">
                <label for="nama_barang" class="form-label" style="color: #1e1e1e;font-size: 18px;font-weight: 400;">Nama Barang</label>
                <div class="input-group w-100" style="height: 60px;">
                    <input required type="text" placeholder="Masukkan Nama Barang" class="p-4 form-control w-100 h-100" id="nama_barang" name="nama_barang" style="border-radius: 20px;border: 1px solid #1e1e1e;background: #FFF;">
                </div>
            </div>
            <div class="mb-3 mt-4">
                <label for="id_kategori" class="form-label" style="color: #1e1e1e;font-size: 18px;font-weight: 400;">Kategori</label>
                <div class="input-group w-100" style="height: 60px">
                    <select name="id_kategori" id="id_kategori" class="form-control px-4" required style="border-radius: 20px;border: 1px solid #1e1e1e;">
                        @foreach ($categories as $category)
                            <option value="" disabled selected hidden>Pilih kategori...</option>
                            <option value="{{ $category->id }}">{{ $category->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mb-3 mt-4">
                <label for="deskripsi" class="form-label" style="color: #1e1e1e;font-size: 18px;font-weight: 400;">Deskripsi</label>
                <div class="input-group w-100">
                    <textarea required placeholder="Masukkan Deskripsi" class="p-4 form-control w-100" name="deskripsi" id="deskripsi" cols="30" rows="8" style="border-radius: 20px;border: 1px solid #1e1e1e;background: #FFF;"></textarea>
                </div>
            </div>
            <div class="mb-3 mt-4 position-relative">
                <div>
                    <label for="" class="form-label" style="color: #1e1e1e; font-size: 18px; font-weight: 400;">Gambar</label>
                </div>
                <div class="position-relative d-flex">
                    <div for="" class="image-label d-flex" style="cursor: pointer;">
                        <img src="{{ asset('img/image-placeholder.png') }}"  class="img-fluid" alt="image-placeholder" style="width: 260px;border-radius: 20px; border: 1px solid #1e1e1e;">
                        <div class="position-absolute" style="width: 45px; height: 45px;">
                            <div class="d-flex justify-content-center align-items-center" style="width: 100%; height: 100%; background: #F6B805; border-radius: 20px;">
                                <i class="bi bi-pencil-square fs-5" style="color: #fff;"></i>
                            </div>
                        </div>
                    </div>
                    <input type="file" id="gambarInput" name="gambar" accept="image/png, image/jpeg, image/jpg, image/webp" style="display: none;" onchange="displayImage(this)">
                </div>
            </div>
            <div class="mb-3 mt-4 d-flex gap-4">
                <div>
                    <label for="" class="form-label" style="color: #1e1e1e; font-size: 18px; font-weight: 400;">Harga</label>
                    <div class="input-group mb-3" style="height: 60px">
                        <span class="input-group-text" style="border-radius: 20px 0 0 20px;">Rp</span>
                        <input name="harga" type="number" class="form-control" placeholder="Harga" style="border-radius: 0 20px 20px 0;">
                    </div>
                </div>
                <div>
                    <label for="" class="form-label" style="color: #1e1e1e; font-size: 18px; font-weight: 400;">Diskon</label>
                    <div class="input-group mb-3" style="height: 60px">
                        <span class="input-group-text" style="border-radius: 20px 0 0 20px;">Rp</span>
                        <input name="diskon" type="number" class="form-control" placeholder="Diskon" style="border-radius: 0 20px 20px 0;">
                    </div>
                </div>
            </div>
            <div class="mb-3 mt-4">
                <label for="sku" class="form-label" style="color: #1e1e1e; font-size: 18px; font-weight: 400;">SKU</label>
                <div class="input-group w-100" style="height: 60px;">
                    <input required type="text" placeholder="Masukkan SKU" class="p-4 form-control w-100 h-100" id="sku" name="sku" style="border-radius: 20px;border: 1px solid #1e1e1e;background: #FFF;">
                </div>
            </div>

            <div class="d-flex justify-content-end gap-3 gap-md-5 mt-4 mb-4">
                <a href="/admin/data/barang" type="button" class="btn d-flex align-items-center justify-content-center" style="width: 100%; max-width: 150px; height: 50px; background: #fff; border-radius: 20px; border: 1px solid #F6B805; color: #F6B805;">
                    <span class="w-100">
                        Batal
                    </span>
                </a>
                <button type="submit" id="submitBtn" class="btn d-flex justify-content-center align-items-center" style="width: 100%; max-width: 150px; height: 50px; background: #F6B805; border-radius: 20px;">
                    <span class="w-100 d-flex gap-2 justify-content-center" style="color: #FFF; text-align: center; font-size: 16px; font-weight: 600;">
                        <i class="bi bi-check-lg" style="color: #FFF;"></i>
                        Simpan
                    </span>
                </button>
            </div>              
        </form>
    </div>

    @push('js')
        <script>
            function displayImage(input) {
                var file = input.files[0];
                if (file) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        input.previousElementSibling.querySelector('img').src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            }
        
            document.querySelector('.image-label').addEventListener('click', function () {
                document.getElementById('gambarInput').click();
            });

            $(function(){
                const token = localStorage.getItem('token');

                $('.form-barang').submit(function(e){
                    e.preventDefault();

                    const frmdata = new FormData(this);

                    $('#submitBtn').prop('disabled', true);

                    $.ajax({
                        url : '/api/products',
                        type : 'POST',
                        data : frmdata,
                        cache: false,
                        contentType: false,
                        processData: false,
                        headers : {
                            "Authorization" : "Bearer " + token
                        },
                        success : function(data){
                            if(data.success) {
                                $('#successAlert').fadeIn().delay(500).fadeOut();

                                setTimeout(function(){
                                    window.location.href = '/admin/data/barang';
                                }, 500);
                            }
                        }
                    })
                });
            });
        </script>
    @endpush
@endsection