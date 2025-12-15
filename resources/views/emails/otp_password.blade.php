<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }}</title>
</head>
<body>
    {{-- <p style="text-align: center">Kode OTP anda adalah {{ $otp }}</p> --}}
    @if(isset($link))
        <small>
            <strong>Reset Password Link:</strong><br>
            <a href="{{ $link }}" target="_blank" class="alert-link">
                {{ $link }}
            </a>
        </small>
    @endif
</body>
</html>
