<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('web/shared/fonts/icomoon/style.css') }}">
    <link rel="stylesheet" href="{{ asset('web/shared/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('web/shared/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('web/shared/css/style.css') }}">

    <title>Create Admin | Internal Tuno</title>

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
        <div class="bg order-1 order-md-2"
            style="background-image: url('{{ asset('web/shared/images/download (32).jpeg') }}');"></div>
        <div class="contents order-2 order-md-1">
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-md-7">
                        <h3>Create <strong>New Admin</strong></h3>
                        <p class="mb-4">Superadmin, use this form to add a new admin account to the internal system.</p>

                        <form action="{{ route('superadmin.admins.store') }}" method="POST">
                            @csrf
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="form-group first">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" name="email" id="email"
                                    placeholder="admin@example.com" value="{{ old('email') }}">
                            </div>

                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" name="username" id="username"
                                    placeholder="adminusername" value="{{ old('username') }}">
                            </div>

                            <div class="form-group position-relative">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" name="password" id="password"
                                    placeholder="Create a password">
                                <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                            </div>

                            <div class="form-group position-relative mb-4">
                                <label for="password_confirmation">Confirm Password</label>
                                <input type="password" class="form-control" name="password_confirmation"
                                    id="password_confirmation" placeholder="Repeat the password">
                                <span toggle="#password_confirmation"
                                    class="fa fa-fw fa-eye field-icon toggle-password"></span>
                            </div>

                            <input type="submit" value="Create Admin" class="btn btn-block btn-primary">
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
        $(document).ready(function() {
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
