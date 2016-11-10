<div class="page-header">
    <h4>采购小组成员:</h4>
</div>
<table class="table">
    <tbody>
    @foreach($memberDeptsWithRoles as $memberDept)
        @foreach($memberDept->role as $roleEntry)
            <tr>
                <td>{{ $deptInfo[$memberDept->dept]->deptCnName }}</td>
                <td>{{ $userInfo[$roleEntry->lanID]->getTriName() }}</td>
            </tr>
        @endforeach
    @endforeach
    </tbody>
</table>