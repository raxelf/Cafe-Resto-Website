<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>OPPA BOX - Login</title>

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="d-flex align-items-center justify-content-center vh-100">
    <div class="container d-flex align-items-center justify-content-center">
        <div class="text-center" style="width: 424px">
            <img src="{{ asset('img/brand.webp') }}" alt="" width="200px" height="80px">
            <form class="form-login text-start" style="margin-top: 60px" method="post" action="/admin">
                @csrf
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Gagal</strong>
                        <p>{{ $errors->first() }}</p>
                    </div>
                @endif
                <div>
                    <label for="email" class="form-label" style="color: #1E1E1E;font-size: 20px;font-weight: 600;">E-MAIL</label>
                    <input type="email" placeholder="Masukkan Alamat E-mail" name="email" class="email p-4 form-control w-100" id="email" aria-describedby="emailHelp" style="height: 60px;border-radius: 20px;border: 1px solid #1E1E1E;background: #FFF;">
                </div>
                <small class="text-danger">
                    @error('email')
                        {{ $message }}
                    @enderror
                </small>
                <div class="mt-2">
                    <label for="password" class="form-label" style="color: #1E1E1E;font-size: 20px;font-weight: 600;">Password</label>
                    <input type="password" placeholder="Password" name="password" class="password p-4 form-control w-100" id="password" style="height: 60px;border-radius: 20px;border: 1px solid #1E1E1E;background: #FFF;"> 
                </div>
                <small class="text-danger">
                    @error('password')
                        {{ $message }}
                    @enderror
                </small>
                <button type="submit" class="btn w-100" style="margin-top: 20px;height:60px;color: #FFF;font-size: 20px;font-weight: 600;border-radius: 20px;background: #F6B805;">Login</button>
              </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        $(function(){
            function setCookie(name,value,days) {
                var expires = "";
                if (days) {
                    var date = new Date();
                    date.setTime(date.getTime() + (days*24*60*60*1000));
                    expires = "; expires=" + date.toUTCString();
                }
                document.cookie = name + "=" + (value || "")  + expires + "; path=/";
            }

            $('.form-login').submit(function(e){
                e.preventDefault()

                const email = $('.email').val()
                const password = $('.password').val()
                const csrf_token = $('meta[name="csrf-token"]').attr('content')
    
                $.ajax({
                    url : '/admin',
                    type : 'POST',
                    data : {
                        email : email,
                        password : password,
                        _token : csrf_token,
                    },
                    success : function(data){
                        if(!data.success){
                            alert(data.message)
                        }
                        
                        localStorage.setItem('token', data.token)
                        window.location.href = '/admin/dashboard';
                    }
                });
            });
        });
    </script>
</body>
</html>