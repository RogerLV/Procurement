@extends('review.display.function.frame')


@section('stageFunction')
    @include('review.display.function.apply.basicinfo')
    <br>
    @include('review.display.function.apply.topics')
    <br>
    @include('review.display.function.apply.addtopic')
    <br>
    @include('review.display.function.apply.invitemember')
    <br>
    @include('review.display.function.apply.submit')
    <br>
    <br>
@endsection