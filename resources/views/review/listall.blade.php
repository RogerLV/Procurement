@extends('layouts.app')


@section('HTMLContent')
    <div class="list-group">
        <?php $i=1 ?>
        @foreach($reviewMeetings as $reviewMeeting)
            <a href="{{ url('review/display/'.$reviewMeeting->id) }}" target="_blank"
               class="list-group-item stage-{{ $reviewMeeting->stage }}">
                {{ $i++ }}. {{ $reviewMeeting->date }}&nbsp;{{ $reviewMeeting->time }}&nbsp;{{ $reviewMeeting->venue }}
            </a>
        @endforeach
    </div>
@endsection