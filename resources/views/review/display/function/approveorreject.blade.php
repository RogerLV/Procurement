<div class="container">
    <div class="col-md-2"></div>
    <div class="col-md-2">
        <button class="btn btn-primary" data-toggle="modal" data-target="#approve-modal"
                data-operation="approve">同意</button>
    </div>
    <div class="col-md-2"></div>
    <div class="col-md-2">
        <button class="btn btn-danger" data-toggle="modal" data-target="#reject-modal"
                data-operation="reject">退回</button>
    </div>
    <div class="col-md-2"></div>
</div>

<div id="approve-modal" class="modal fade">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">确认同意</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>填写意见:</label>
                    <textarea class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary commit-button" data-operation="approve" data-dismiss="modal">通过</button>
                <button class="btn btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>

<div id="reject-modal" class="modal fade">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">确认退回</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>填写意见:</label>
                    <textarea class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger commit-button" data-operation="reject" data-dismiss="modal">退回</button>
                <button class="btn btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('button.commit-button').click(function () {
            var comment = $(this).parents('div.modal-content').find('textarea').val();
            var operation = $(this).data('operation');

            $.ajax({
                headers: headers,
                url: "{{ route(ROUTE_NAME_REVIEW_STAGE_APPROVE) }}",
                data: {
                    operation: operation,
                    comment: comment,
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