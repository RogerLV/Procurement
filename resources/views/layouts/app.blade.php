<!DOCTYPE html>

<html>
<head>
    <meta http-equiv="X-UA-COMPATIBLE" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script type="text/javascript" src="{{ asset('js/jquery-2.1.3.min.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>

    <title>{{ $title or APP_NAME_PURCHASE_SYSTEM }}</title>

    @yield('CSSContent')

</head>
<body>
<div class="container text-center">
    <button type="button" class="btn btn-default" style="display: block; width: 100%"
            onclick="localtion.href='{{ route('welcome') }}'">
        <span class="glyphicon glyphicon-home" aria-hidden="true"></span>
    </button>
</div>

<div class="container">
    <div class="col-md-1"></div>
    <div class="col-md-10" id="main-container">
        <h2>{{ $title or APP_NAME_PURCHASE_SYSTEM }}</h2>
        <br>

        @yield('HTMLContent')

    </div>
    <div class="col-md-1"></div>
</div>
</body>
</html>

<script type="text/javascript">

    @yield('javascriptContent')

</script>