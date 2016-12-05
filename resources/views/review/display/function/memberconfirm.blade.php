@extends('review.display.function.frame')


@section('stageFunction')
    <table class="table table-bordered">
        @foreach($participants as $participant)
            <tr>
                <td>{{ $deptInfo->get($participant->user->dept)->deptCnName }}</td>
                <td>{{ $participant->user->getTriName() }}</td>
                <td>
                    @if(is_null($participant->willAttend))
                        待回复
                    @elseif($participant->willAttend)
                        参会
                    @else
                        不参会
                    @endif
                </td>
            </tr>
        @endforeach
    </table>

    @if(is_null($participants->get($loginUserLanID)->willAttend))
        @include('review.display.function.approveorreject')
    @endif

@endsection

