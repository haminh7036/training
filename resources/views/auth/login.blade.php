<!DOCTYPE html>
<html lang="{{App::getLocale()}}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <img src="{{asset('asset/login/logo.png')}}" alt="" srcset="">
            </div>
            <div class="col-sm-6 mx-auto">
                <form class="form-horizontal" id="form-login" action="{{route('auth.postLogin')}}" method="POST">
                    @csrf
                    <div class="form-group col-12">
                        <input type="email" class="input-field form-control @error('email') is-invalid @enderror" 
                            name="email" placeholder="Email" id="email">
                        @error('email')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                    <div class="form-group col-12">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                            name="password" placeholder="Mật khẩu" id="password">
                        @error('password')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>        
                    <div class="form-group d-sm-flex">
                            <div class="checkbox col">
                                <label>
                                    <input type="checkbox" name="remember" value="true"> Ghi nhớ
                                </label>
                            </div>
                            <div class="col text-sm-right">
                                <button type="submit" class="btn btn-sm btn-info">Đăng nhập</button>    
                            </div>                        
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/popper.js') }}"></script>
<script>
    $(document).ready(function () {
        $("#form-login").validate({
            onfocusout: false,
            onkeyup: false,
            onclick: false,
            errorElement: "small",
            errorClass: "is-invalid text-danger",
            rules: {
                "email" : {
                    required : true,
                    email: true,
                },
                "password" : {
                    required : true
                }
            },
            messages: {
                "email" : {
                    required: "Email là bắt buộc",
                    email: "Email không đúng định dạng",
                },
                "password" : {
                    required : "Mật khẩu là bắt buộc"
                }
            },
            highlight: function ( element, errorClass) { 
                $ ( element ).addClass(errorClass).removeClass("text-danger");
            }, 
        })
    });
</script>
</html>