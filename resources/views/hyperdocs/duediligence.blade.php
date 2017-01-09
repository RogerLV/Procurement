@extends('layouts.hyperdoc')


@section('HTMLContent')
    @foreach($records as $record)
        <div class="panel panel-default">
            <div class="panel-heading"><h4>{{ $record->request }}</h4></div>
            <div class="panel-body">
                <p>{{ $record->answer }}</p>
            </div>
        </div>
    @endforeach
@endsection