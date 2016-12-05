@extends('layouts.app')


@section('HTMLContent')
    <?=$stageView?>
    <br>
    <div class="page-header">
        <h4>流转记录:</h4>
    </div>

    <ul class="list-group">
        @foreach($logs as $entry)
            @if($entry->toStage > $entry->fromStage)
                <li class="list-group-item list-group-item-info">
            @elseif($entry->toStage < $entry->fromStage)
                <li class="list-group-item list-group-item-danger">
            @else
                <li class="list-group-item list-group-item-default">
                    @endif
                    <h4 class="list-group-item-heading">
                        {{ $entry->timeAt }}: {{ $entry->operator->getTriName() }} {{ $stageNames[$entry->fromStage] }}
                    </h4>
                    @if(!empty($entry->comment))
                        <p class="list-group-item-text">{{ $entry->comment }}</p>
                    @endif
                </li>
            @endforeach
    </ul>
@endsection