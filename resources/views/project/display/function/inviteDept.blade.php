@extends('project/display/function/frame')

@section('stageFunction')

    @if($projectIns->involveReview)
        <ol>
            <i><li>采购部门相关人员不得占到采购小组成员半数以上,原则上小组成员不少于3人。</li></i>
            <i><li>20万新元(含)以上的项目,采购小组成员不应少于5人。</li></i>
        </ol>
    @else
        <p><i>原则上至少2人参加或组成跨部门采购小组。</i></p>
    @endif

    <div class="form-group">
        <label for="select-member-amounts">选择人数:</label>
        <select class="form-control" required id="select-member-amounts">
            @foreach(range(2, 10) as $val)
                <option value="{{ $val }}">{{ $val }}</option>
            @endforeach
        </select>
    </div>

    <br>
    <label for="select-member-amounts">选择部门:</label>
    <div>
    @foreach($deptInfo as $dept => $department)
        @continue($department->isBranch)
        <div class="checkbox">
            <label class="checkbox">
                <input type="checkbox" name="invite-dept" value="{{ $dept }}">
                <p>{{ $department->deptCnName }}</p>
            </label>
        </div>
    @endforeach
    </div>

    <button class="btn btn-primary btn-xs" id="btn-toggle-branch">显示网点</button>
    <div id="branch-zone" style="display: none;">
    @foreach($deptInfo as $dept => $department)
        @continue(!$department->isBranch)
        <div class="checkbox">
            <label class="checkbox">
                <input type="checkbox" name="invite-dept" value="{{ $dept }}">
                <p>{{ $department->deptCnName }}</p>
            </label>
        </div>
    @endforeach
    </div>

    <div class="container outer">
        <button class="btn btn-primary" id="submit-inviting-dept">邀请</button>
    </div>

    <div id="invite-dept-confirm-modal" class="modal fade">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" type="button" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">邀请部门确认</h4>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" id="invite-dept-commit" data-dismiss="modal">确认</button>
                    <button class="btn btn-default" data-dismiss="modal">取消</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#btn-toggle-branch').click(function () {
                $('#branch-zone').toggle();
            });

            $('#submit-inviting-dept').click(function () {
                var selected = $("input[name='invite-dept']:checked");
                var modalBody = $('#invite-dept-confirm-modal').find('div.modal-body').text('');

                modalBody.append('<h4>邀请人数: '+$('#select-member-amounts').val()+"</h4>");

                if (selected.length == 0) {
                    modalBody.append('不邀请其他部门。');
                } else {
                    modalBody.append($('<h4/>', {
                        text: '邀请以下部门:'
                    }));

                    $("input[name='invite-dept']:checked").each(function () {
                        modalBody.append('<p>'+$(this).next().html()+'</p>');
                    });
                }
                $('#invite-dept-confirm-modal').modal('show');
            });

            $('#invite-dept-commit').click(function () {
                var inviteDepts = [];
                $("input[name='invite-dept']:checked").each(function(idx, el) {
                    inviteDepts.push(el.value);
                });

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')
                    },
                    url: "{{ route(ROUTE_NAME_STAGE_INVITE_DEPT) }}",
                    data: {
                        membercount: $('#select-member-amounts').val(),
                        inviteddepts: inviteDepts,
                        projectid: projectID
                    },
                    type: 'POST',
                    success: function (data) {
                        handleReturn(data);
                    }
                });
            });
        });
    </script>
@endsection
