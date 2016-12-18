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
            <h2>采购方式传签</h2>
        </div>



        <h4>委员:</h4>
        <table class="table">
            <tr>
                <th>#</th>
                <th>委员姓名</th>
                <th>委员意见</th>
                <th>传签时间</th>
            </tr>
            <?php $i=1 ?>
            @foreach($memberLogs as $log)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $log->operator->getDualName() }}</td>
                    <td>{{ $passSignValues[$log->data1] }}</td>
                    <td>{{ $log->timeAt }}</td>
                </tr>
            @endforeach
        </table>
        <br>
        <br>

        <h4>副主任:</h4>
        <table class="table">
            @foreach($viceDirectorLogs as $log)
                <tr>
                    <td>{{ $log->operator->getDualName() }}</td>
                    <td>{{ $passSignValues[$log->data1] }}</td>
                    <td>{{ $log->timeAt }}</td>
                </tr>
            @endforeach
        </table>

        <h4>主任:</h4>
        <table class="table">
            @foreach($directorLogs as $log)
                <tr>
                    <td>{{ $log->operator->getDualName() }}</td>
                    <td>{{ $passSignValues[$log->data1] }}</td>
                    <td>{{ $log->timeAt }}</td>
                </tr>
            @endforeach
        </table>

    </div>
    <div class="col-md-1"></div>
</div>
</body>
</html>