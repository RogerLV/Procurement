@extends('layouts.hyperdoc')


@section('HTMLContent')
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
@endsection
