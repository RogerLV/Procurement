@extends('layouts.app')


@section('HTMLContent')
    @foreach($negotiations as $negotiation)
        <h4>第{{ $negotiation->roundNo }}轮谈判记录</h4>
        <table class="table table-bordered">
            <tr>
                <th width="20%">时间</th>
                <td>{{ $negotiation->time }}</td>
            </tr>
            <tr>
                <th>地点</th>
                <td>{{ $negotiation->venue }}</td>
            </tr>
            <tr>
                <th>参与人员</th>
                <td>{{ $negotiation->participants }}</td>
            </tr>
            <tr>
                <th>谈判记录</th>
                <td>{!! $negotiation->content !!}</td>
            </tr>
        </table>
        <br>
    @endforeach
@endsection