@extends('review.display.function.frame')


@section('stageFunction')

    <table class="table">
        @foreach($logs as $log)
            <tr>
                <td>{{ $log->operator->getDualName() }}</td>
                <td>{!!  empty($log->comment) ? '无意见' : $log->comment !!}</td>
            </tr>
        @endforeach
    </table>

    @if(!$commented)
        @include('review.display.function.membercomments.commentornot')
    @endif
@endsection
