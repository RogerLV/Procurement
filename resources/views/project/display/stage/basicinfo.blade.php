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

<div class="panel panel-default">
    <div class="panel-heading"><h4>项目基本信息</h4></div>
    <div class="panel-body">

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
                    <td><h4>项目发起部门:</h4></td>
                    <td>{{ $project->department->deptCnName }}</td>
                </tr>
                <tr>
                    <td><h4>项目负责人: </h4></td>
                    <td>{{ $project->submitter->getTriName() }}</td>
                </tr>
                <tr>
                    <td><h4>采购方式:</h4></td>
                    <td>{{ $project->getProcurement() }}</td>
                </tr>
                <tr>
                    <td><h4>纳入采购评审: </h4></td>
                    <td>{{ $project->involveReview ? '是' : '否' }}</td>
                </tr>
                @if(!is_null($project->summary))
                    <tr>
                        <td><h4>结果总结:</h4></td>
                        <td>{{ $project->summary }}</td>
                    </tr>
                @endif
                <tr>
                    <td valign="top"><h4>采购小组成员:</h4></td>
                    <td>
                    </td>
                </tr>
            </tbody>
        </table>

        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>部门</th>
                <th>成员</th>
            </tr>
            </thead>
            <tbody>
            <?php $i=1 ?>
            @foreach($projectDeptWithRoles as $projectRoleDept)
                @foreach($projectRoleDept->role as $role)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ $projectRoleDept->department->deptCnName }}</td>
                        <td>{{ $role->user->getTriName() }}</td>
                    </tr>
                @endforeach
            @endforeach
            </tbody>
        </table>

    </div>
</div>


<script type="text/javascript">
    var projectID = "{{ $project->id }}";
</script>
