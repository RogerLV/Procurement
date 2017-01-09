<!DOCTYPE html>

<html>
<head>
    <meta http-equiv="X-UA-COMPATIBLE" content="IE=edge">

    <script type="text/javascript" src="{{ asset('js/jquery-2.1.3.min.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>

    <title>采购方式传签</title>
</head>

<body>

<div class="container">
    <div class="col-md-1"></div>
    <div class="col-md-10" id="main-container">
        <div class="page-header">
            <h2>{{ $title }}</h2>
        </div>

        @yield('HTMLContent')

    </div>
    <div class="col-md-1"></div>

</div>
</body>
</html>