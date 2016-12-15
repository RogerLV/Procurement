<tr>
    <td>{{ $participant->user->department->deptCnName }}</td>
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