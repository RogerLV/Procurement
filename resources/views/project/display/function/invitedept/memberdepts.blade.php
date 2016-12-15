<table class="table">
    <tbody id="member-dept-list">
    @foreach($memberDepts as $memberDept)
        <tr>
            <td>{{ $deptInfo[$memberDept->dept]->deptCnName }}</td>
            <td>{{ $memberDept->memberAmount }}人</td>
            <td>
                <button class="btn btn-danger btn-xs remove-member-dept"
                        data-member-dept-id="{{ $memberDept->id }}">
                    <span class="glyphicon glyphicon-remove"></span>
                </button>
            </td>
        </tr>
    @endforeach
    <tr>
        <td align="center" colspan="5">
            <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#invite-dept-modal">
                <span class="glyphicon glyphicon-plus"></span>
            </button>
        </td>
    </tr>
    </tbody>
</table>

<div id="invite-dept-modal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">邀请部门确认</h4>
            </div>

            <div class="modal-body">

                @if($projectIns->involveReview)
                    <ol>
                        <i><li>采购部门相关人员不得占到采购小组成员半数以上,原则上小组成员不少于3人。</li></i>
                        <i><li>20万新元(含)以上的项目,采购小组成员不应少于5人。</li></i>
                    </ol>
                @else
                    <p><i>原则上至少2人参加或组成跨部门采购小组。</i></p>
                @endif

                <div class="form-group">
                    <label>选择部门:</label>
                    <select class="form-control" id="dept-selector">
                        <option disabled selected></option>
                        @foreach($deptInfo as $dept => $department)
                            <option value="{{ $dept }}">{{ $department->deptCnName }}</option>
                        @endforeach
                    </select>
                </div>
                <br>

                <div class="form-group">
                    <label>选择人数:</label>
                    <select class="form-control" id="select-member-amounts">
                        @foreach(range(1, 10) as $val)
                            <option value="{{ $val }}">{{ $val }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-primary" id="invite-dept-button" data-dismiss="modal">邀请</button>
                <button class="btn btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {

        var removeMemberDept = function () {
            var tr = $(this).parents('tr');
            var memberDeptID = $(this).data('member-dept-id');

            $.ajax({
                headers: headers,
                url: "{{ route('MemberDepartmentRemove') }}",
                data: {
                    memberDeptID: memberDeptID,
                    projectid: projectID
                },
                type: 'POST',
                success: function (data) {
                    handleReturn(data, function () {
                        tr.hide();
                    });
                }
            });
        }

        $('button.remove-member-dept').click(removeMemberDept);

        $('#invite-dept-button').click(function () {
            var dept = $('#dept-selector').val();

            if (0 == dept.length) {
                setAlertText('请选择部门');
                $('#alert-modal').modal('show');
                return;
            }

            $.ajax({
                headers: headers,
                url: "{{ route('MemberDepartmentAdd') }}",
                data: {
                    memberCount: $('#select-member-amounts').val(),
                    dept: dept,
                    projectid: projectID
                },
                type: "POST",
                success: function (data) {
                    handleReturn(data, function () {
                        // render new member dept
                        $('#member-dept-list').prepend($('<tr>').append($('<td>', {
                            text: data.info.deptInfo.deptCnName
                        })).append($('<td>', {
                            text: data.info.memberDept.memberAmount + "人"
                        })).append($('<td>').append($('<button>', {
                            class: 'btn btn-danger btn-xs'
                        }).append($('<span>', {
                            class: 'glyphicon glyphicon-remove'
                        }))).bind('click', removeMemberDept).data(
                                'member-dept-id',
                                data.info.memberDept.id
                        )));
                    });
                }
            });
        });
    });
</script>