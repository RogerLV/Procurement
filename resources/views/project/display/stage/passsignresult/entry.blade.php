<tr>
    <td>{{ $member->department->deptCnName }}</td>
    <td>{{ $member->user->getTriName() }}</td>
    <td align="left">
        <?php $sign = $signs->where('lanID', $member->lanID)->whereLoose('roleID', $member->roleID)->first() ?>
        @if(is_null($sign))
            未操作
        @else
            {{ $passSignValues[$sign->data1] }}
        @endif
    </td>
</tr>