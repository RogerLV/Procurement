@extends('layouts.app')


@section('HTMLContent')
    @include('review.stage.apply.basicinfo')
    <br>
    @include('review.stage.apply.topics')
    <br>
    @include('review.stage.apply.addtopic')
    <br>
    @include('review.stage.apply.invitemember')
    <br>
    @include('review.stage.apply.submit')
    <br>
    <br>
@endsection
