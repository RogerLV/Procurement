<!DOCTYPE html>

<html>
<head>
    <meta http-equiv="X-UA-COMPATIBLE" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script type="text/javascript" src="{{ asset('js/jquery-2.1.3.min.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>

    <title>{{ $title or APP_NAME_PURCHASE_SYSTEM }}</title>
</head>

<body>

<style type="text/css">
    .modal {
        text-align: center;
    }

    @media screen and (min-width: 768px) {
        .modal:before {
            display: inline-block;
            vertical-align: middle;
            content: "";
            height: 100%;
        }
    }

    .modal-dialog {
        display: inline-block;
        text-align: left;
        vertical-align: middle;
    }
</style>

@yield('CSSContent')

<div class="container text-center">
    <button type="button" class="btn btn-default" style="display: block; width: 100%"
            onclick="localtion.href='{{ route(ROUTE_NAME_WELCOME) }}'">
        <span class="glyphicon glyphicon-home" aria-hidden="true"></span>
    </button>
</div>

<div id="alert-modal" class="modal fade">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="modal-text-area">

            </div>
        </div>
    </div>
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
    var setAlertText = function(content) {
        $('#modal-text-area').text('').append(content);
    }

    var handleReturn = function (data, successHandler) {
        if ('good' == data.status) {
            successHandler = successHandler || function () {
                window.location.reload();
            }
            successHandler();
        } else if ('ERR001' == data.status) {
            location.href = "http://"+"{{ env('PLATFORM_HOST') }}"+"/platform/index.php";
        } else {
            setAlertText(data.status+": "+data.errorInfo);
            $("#alert-modal").modal('show');
        }
    }
</script>

@yield('javascriptContent')
