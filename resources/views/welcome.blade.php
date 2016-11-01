@extends('layouts.app')


@section('HTMLContent')
    <h3>{{ $userInfo->uEngName }} {{ $userInfo->uCnName }}</h3>
    <h3>{{ $deptInfo->deptEngName }}</h3>
    <h3>{{ $deptInfo->deptCnName }}</h3>
@endsection