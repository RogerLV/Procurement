<button class="btn btn-primary btn-block" data-toggle="modal" data-target="#invite-dept-confirm-modal">
    提交
</button>

<div id="invite-dept-confirm-modal" class="modal fade">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">确认提交</h4>
            </div>

            <div class="modal-body">
                <p>邀请部门信息提交后不可更改。</p>
            </div>

            <div class="modal-footer">
                <button class="btn btn-primary" id="invite-dept-commit" data-dismiss="modal">提交</button>
                <button class="btn btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        $('#invite-dept-commit').click(function () {
            $.ajax({
                headers: headers,
                url: "{{ route('StageComplete') }}",
                data: {projectid: projectID},
                type: 'POST',
                success: function (data) {
                    handleReturn(data);
                }
            });
        });
    });
</script>