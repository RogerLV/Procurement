@extends('layouts.app')


@section('CSSContent')
    <style type="text/css">
        th, td.numbers {
            text-align: center;
            vertical-align: middle;
        }
    </style>
@endsection


@section('HTMLContent')
    @foreach($vendors as $vendor)
        <div class="page-header">
            <h4>{{ $vendor->name }}</h4>
        </div>
        <table class="table table-bordered" style="table-layout: fixed">
            <thead>
                <th style="width: 50px;">#</th>
                <th style="width: 120px">打分项</th>
                <th style="width: 500px">具体内容</th>
                <th style="width: 50px;">权重</th>
                @foreach($members as $member)
                    <th style="width: 100px">{{ $member->user->uEngName }}</th>
                @endforeach
                <th colspan="2" style="width: 300px">备注</th>
            </thead>
            <tbody>
                <?php $i=1; ?>
                @foreach($scoreItems as $item)
                    @continue(0 == $item->weight)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ $item->item }}</td>
                        <td>{!! $item->content !!}</td>
                        <td>{{ $item->weight }}</td>
                        @foreach($members as $member)
                            <td class="numbers"><p>{{ $scoreMatrix[$vendor->id][$item->id][$member->lanID] or '未填写' }}</p></td>
                        @endforeach
                        <td colspan="2">{{ $item->comment }}</td>
                    </tr>
                @endforeach
                <tr>
                    <th colspan="4">总分</th>
                    @foreach($members as $member)
                        <td class="numbers">{{ $sumScoreMatrix[$vendor->id][$member->lanID] or '无' }}</td>
                    @endforeach
                    <th>最终得分</th>
                    <td>{{ $finalScores[$vendor->id] or '无' }}</td>
                </tr>
            </tbody>
        </table>
    @endforeach
@endsection


@section('javascriptContent')

@endsection