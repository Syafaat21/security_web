<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OTP Email</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{asset('adminlte/plugins/fontawesome-free/css/all.min.css')}}">
    <link rel="stylesheet" href="{{asset('adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('adminlte/dist/css/adminlte.min.css')}}">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <b>Security Web</b> - OTP Email
    </div>
    <div class="card">
        <div class="card-body login-card-body">

            @if(session('failed'))
            <div class="alert alert-danger">{{ session('failed') }}</div>
            @endif
            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif
        <form action="/verify/{{ $unique_id }}" method="post">
            @method('PUT')
            @csrf
            <div class="input-group mb-3">
            <input type="number" name="otp" class="form-control" placeholder="xxxxxx">
            <div class="input-group-append">
                <div class="input-group-text">
                <span class="fas fa-envelope"></span>
                </div>
            </div>
            </div>
            <div class="row">
            <div class="col-8">

            </div>

            <div class="col-4">
                <button type="submit" class="btn btn-primary btn-block">Submit</button>
            </div>

            </div>
        </form>
        <p class="mb-0">
            <a href="{{ url('/verify/' . $unique_id . '/resend') }}" class="text-center">Resend OTP</a>
        </p>
        </div>
    </div>
</div>

<script src="{{asset('adminlte/plugins/jquery/jquery.min.js')}}"></script>
<script src="{{asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('adminlte/dist/js/adminlte.min.js')}}"></script>

</body>
</html>
