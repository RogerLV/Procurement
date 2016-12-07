<div class="container">
    <div class="col-md-2"></div>
    <div class="col-md-2">
        <button class="btn btn-primary" data-toggle="modal" data-target="#approve-modal">无意见</button>
    </div>
    <div class="col-md-2"></div>
    <div class="col-md-2">
        <button class="btn btn-danger" data-toggle="modal" data-target="#reject-modal">填写意见</button>
    </div>
    <div class="col-md-2"></div>
</div>

<div id="approve-modal" class="modal fade">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">确认无意见?</h4>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" id="approve-button" data-dismiss="modal">确定</button>
                <button class="btn btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>

<div id="reject-modal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">填写意见</h4>
            </div>
            <div class="modal-body">
                <textarea class="form-control" rows="5"></textarea>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" id="reject-button" data-dismiss="modal">提交</button>
                <button class="btn btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#approve-button').click(function () {
            $.ajax({
                headers: headers,
                url: "{{ route('ReviewStageApprove') }}",
                data: {
                    operation: 'approve',
                    reviewMeetingID: reviewID
                },
                type: 'POST',
                success: function (data) {
                    handleReturn(data);
                }
            });
        });

        $('#reject-button').click(function () {
            $comment = $.trim($(this).parents('div.modal-content').find('textarea').val());
            if(0 == $comment.length) {
                setAlertText('意见不能为空。');
                $('#alert-modal').modal('show');
                return;
            }

            $.ajax({
                headers: headers,
                url: "{{ route('ReviewStageApprove') }}",
                data: {
                    operation: 'reject',
                    comment: $(this).parents('div.modal-content').find('textarea').val(),
                    reviewMeetingID: reviewID
                },
                type: 'POST',
                success: function (data) {
                    handleReturn(data);
                }
            });
        });
    });
</script>