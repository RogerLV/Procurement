@extends('project/display/function/frame')


@section('stageFunction')
    <table class="table">
        @foreach($fullCommittee as $member)
            <tr>
                <td>{{ $member->department->deptCnName }}</td>
                <td>{{ $member->user->getTriName() }}</td>
                <td align="left">
                    @if($signs->has($member->lanID))
                        {{ $passSignValues[$signs->get($member->lanID)->data1] }}
                    @else
                        未操作
                    @endif
                </td>
            </tr>
        @endforeach
    </table>

    @if($signable)

        @include('project/display/function/approveorreject')

    @endif
@endsection