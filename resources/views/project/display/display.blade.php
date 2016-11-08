@extends('layouts.app')


@section('HTMLContent')

    <?=$stageView?>
    <br>

    @include('project/display/documents')
    <br>

    @include('project/display/stageloglist')
    <br>

    @include('project/display/conversation')
    <br>

@endsection
