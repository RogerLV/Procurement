<div class="alert alert-info"  style="font-size: 18px">
    <table>
        <tr>
            <td><strong>当前阶段:</strong></td>
            <td>{{ $stageNames[$project->stage] }}</td>
        </tr>
        <tr>
            <td><strong>当前有权执行人:&nbsp;</strong></td>
            <td>{{ implode(', ', $stageIns->getExecuters()) }}</td>
        </tr>
    </table>
</div>

<table>
    <tbody>
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
        <td>{{ $project->department->deptCnName }}: {{ $project->submitter->getTriName() }}</td>
    </tr>
    <tr>
        <td><h4>采购方式:</h4></td>
        <td>{{ $project->getProcurement() }}</td>
    </tr>
    <tr>
        <td><h4>纳入采购评审: </h4></td>
        <td>{{ $project->involveReview ? '是' : '否' }}</td>
    </tr>
    </tbody>
</table>

<script type="text/javascript">
    var projectID = "{{ $project->id }}";
</script>
