@extends('layouts.app')


@section('HTMLContent')

    @include('project/display/basicinfo')
    <br>

    @include('project/display/documents')
    <br>

    @include('project/display/stageloglist')
    <br>

    @include('project/display/conversation')
    <br>

@endsection
