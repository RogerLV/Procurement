<table>
    <tbody>
    <tr>
        <td width="20%" valign="top"><h4>项目编号: </h4></td>
        <td>{{ $project->givenID }}</td>
    </tr>
    <tr>
        <td valign="top"><h4>项目主题: </h4></td>
        <td>{{ $project->name }}</td>
    </tr>
    <tr>
        <td valign="top"><h4>项目背景: </h4></td>
        <td>{{ $project->background }}</td>
    </tr>
    <tr>
        <td><h4>项目负责人: </h4></td>
        <td>{{ $deptInfo[$project->dept]->deptCnName }}: {{ $userInfo[$project->lanID]->getDualName() }} {{ $userInfo[$project->lanID]->IpPhone }}</td>
    </tr>
    <tr>
        <td><h4>纳入采购评审: </h4></td>
        <td>{{ $project->involveReview ? '是' : '否' }}</td>
    </tr>
    </tbody>
</table>
