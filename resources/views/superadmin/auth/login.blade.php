<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="web/shared/fonts/icomoon/style.css">

    <link rel="stylesheet" href="web/shared/css/owl.carousel.min.css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="web/shared/css/bootstrap.min.css">

    <!-- Style -->
    <link rel="stylesheet" href="web/shared/css/style.css">

    <title>Tuno Login | For Internal</title>
</head>

<body>


    <div class="d-lg-flex half">
        <div class="bg order-1 order-md-2" style="background-image: url('web/shared/images/download (32).jpeg');"></div>
        <div class="contents order-2 order-md-1">

            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-md-7">
                        <h3>Login to <strong>Internal Tuno</strong></h3>
                        <p class="mb-4">
                            You're just one login away from the heart of Tuno. Manage, monitor, and make things happen.
                        </p>

                        <form action="{{ route('superadmin.login.post') }}" method="POST">
                            @csrf

                            {{-- Tampilkan error jika login gagal --}}
                            @if ($errors->has('login'))
                                <div class="alert alert-danger">{{ $errors->first('login') }}</div>
                            @endif

                            <div class="form-group first">
                                <label for="username">Username or Email</label>
                                <input type="text" class="form-control" name="login" id="username" placeholder="your-email@gmail.com" value="{{ old('login') }}">
                            </div>

                            <div class="form-group last mb-3">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" name="password" id="password" placeholder="Your Password">
                            </div>

                            <div class="d-flex mb-5 align-items-center">
                                <label class="control control--checkbox mb-0"><span class="caption">Remember me</span>
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



    <script src="web/shared/js/jquery-3.3.1.min.js"></script>
    <script src="web/shared/js/popper.min.js"></script>
    <script src="web/shared/js/bootstrap.min.js"></script>
    <script src="web/shared/js/main.js"></script>
</body>

</html>
