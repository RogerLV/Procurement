<table>
    <tbody>
        <tr>
            <td width="113px"><h4>参与部门:</h4></td>
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