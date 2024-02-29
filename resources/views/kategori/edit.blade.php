@extends('layout.app')

@section('title', 'Edit Kategori')

@section('content')
    <div class="container">
        <div class="alert position-fixed" style="display: none;background: #FFF;box-shadow: 0px 0px 12px 0px rgba(0, 0, 0, 0.25);top: 2%; right: 2%; z-index: 999" role="alert" id="successAlert">
            <div class="d-flex gap-3 justify-content-start align-items-center">
                <i class="bi bi-check-circle-fill fs-5" style="color:#04BC00;"></i> 
                <span style="color: #1e1e1e;text-align: center;font-size: 16px;font-weight: 400;">
                    Kategori Berhasil diperbarui.
                </span>
            </div>
        </div>

        <h4 style="margin-top:70px;color: #1e1e1e;font-size: 28px;font-weight: 400;">Edit Kategori</h4>

        <form class="form-kategori">
            <div class="mb-3 mt-4">
                <label for="nama_kategori" class="form-label" style="color: #1e1e1e;font-size: 18px;font-weight: 400;">Nama Kategori</label>
                <div class="input-group w-100" style="height: 60px;">
                    <input required type="text" placeholder="Masukkan Nama Kategori" class="p-4 form-control w-100 h-100" id="nama_kategori" name="nama_kategori" style="border-radius: 20px;border: 1px solid #1e1e1e;background: #FFF;">
                </div>
            </div>
            <div class="mb-3 mt-4">
                <label for="deskripsi" class="form-label" style="color: #1e1e1e;font-size: 18px;font-weight: 400;">Deskripsi</label>
                <div class="input-group w-100">
                    <textarea required placeholder="Masukkan Deskripsi" class="p-4 form-control w-100" name="deskripsi" id="deskripsi" cols="30" rows="8" style="border-radius: 20px;border: 1px solid #1e1e1e;background: #FFF;"></textarea>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-3 gap-md-5">
                <a href="/admin/data/kategori" type="button" class="btn d-flex align-items-center justify-content-center" style="width: 100%; max-width: 150px; height: 50px; background: #fff; border-radius: 20px; border: 1px solid #F6B805; color: #F6B805;">
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
            $(document).ready(function(){
                const token = localStorage.getItem('token');
                const url = window.location.pathname;
                const id = url.substring(url.lastIndexOf('/') + 1);

                $.ajax({
                    url: '/api/categories/' + id,
                    type: 'GET',
                    headers : {
                        "Authorization" : "Bearer " + token
                    },
                    success: function(data) {
                        $('input[name="nama_kategori"]').val(data.data.nama_kategori);
                        $('textarea[name="deskripsi"]').val(data.data.deskripsi);
                    }
                });
            });

            $(function(){
                const token = localStorage.getItem('token');
                const url = window.location.pathname;
                const id = url.substring(url.lastIndexOf('/') + 1);

                $('.form-kategori').submit(function(e){
                    e.preventDefault();

                    const frmdata = new FormData(this);

                    $('#submitBtn').prop('disabled', true);

                    $.ajax({
                        url : '/api/categories/'+ id + '?_method=PUT',
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
                                    window.location.href = '/admin/data/kategori';
                                }, 500);
                            }
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection