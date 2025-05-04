<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('web/shared/fonts/icomoon/style.css') }}">
    <link rel="stylesheet" href="{{ asset('web/shared/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('web/shared/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('web/shared/css/style.css') }}">

    <title>Tuno Login | Admin Panel</title>

    <style>
        .field-icon {
            position: absolute;
            top: 38px;
            right: 15px;
            cursor: pointer;
            z-index: 2;
            color: #999;
        }
    </style>
</head>

<body>

    <div class="d-lg-flex half">
        <div class="bg order-1 order-md-2" style="background-image: url('{{ asset('web/shared/images/download (32).jpeg') }}');"></div>
        <div class="contents order-2 order-md-1">
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-md-7">
                        <h3>Login to <strong>Admin Panel</strong></h3>
                        <p class="mb-4">Access your tools and dashboards as an admin of Tuno.</p>

                        <form action="{{ route('admin.login.post') }}" method="POST">
                            @csrf

                            @if ($errors->has('login'))
                                <div class="alert alert-danger">{{ $errors->first('login') }}</div>
                            @endif

                            <div class="form-group first">
                                <label for="username">Username or Email</label>
                                <input type="text" class="form-control" name="login" id="username"
                                       placeholder="your-email@gmail.com" value="{{ old('login') }}">
                            </div>

                            <div class="form-group last mb-3 position-relative">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" name="password" id="password"
                                       placeholder="Your Password">
                                <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                            </div>

                            <div class="d-flex mb-5 align-items-center">
                                <label class="control control--checkbox mb-0">
                                    <span class="caption">Remember me</span>
                                    <input type="checkbox" name="remember" checked="checked" />
                                    <div class="control__indicator"></div>
                                </label>
                                <span class="ml-auto"><a href="#" class="forgot-pass">Forgot Password</a></span>
                            </div>

                            <input type="submit" value="Log In" class="btn btn-block btn-primary">
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('web/shared/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('web/shared/js/popper.min.js') }}"></script>
    <script src="{{ asset('web/shared/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('web/shared/js/main.js') }}"></script>

    <script>
        $(document).ready(function(){
            $(".toggle-password").click(function() {
                const input = $($(this).attr("toggle"));
                const type = input.attr("type") === "password" ? "text" : "password";
                input.attr("type", type);
                $(this).toggleClass("fa-eye fa-eye-slash");
            });
        });
    </script>
</body>

</html>
