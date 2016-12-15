@extends('review.display.function.frame')


@section('stageFunction')

    @if($confirmable)
        @include('review.display.function.approveorreject')
    @endif

@endsection

