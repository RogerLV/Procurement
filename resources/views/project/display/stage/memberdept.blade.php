<table>
    <tbody>
        <tr>
            <td><h4>其他参与部门:</h4></td>
            <td>
                @forelse($deptInfo as $deptEntry)
                    {{ $deptEntry->department->deptCnName }} &nbsp;
                @empty
                    无其他部门
                @endforelse
            </td>
        </tr>
    </tbody>
</table>