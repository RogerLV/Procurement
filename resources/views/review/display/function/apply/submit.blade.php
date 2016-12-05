<div class="outer">
    <button class="btn btn-lg btn-primary" data-toggle="modal" data-target="#confirm-apply-review-modal">发起</button>
</div>

<div id="confirm-apply-review-modal" class="modal fade">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">确定发起本次采购评审?</h4>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" id="confirm-apply-review-button" data-dismiss="modal">确定</button>
                <button class="btn btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        $('#confirm-apply-review-button').click(function () {
            $.ajax({
                headers: headers,
                url: "{{ route(ROUTE_NAME_REVIEW_STAGE_COMPLETE) }}",
                data: {
                    reviewMeetingID: "{{ $reviewMeetingIns->id }}"
                },
                type: 'POST',
                success: function (data) {
                    handleReturn(data, function () {
                        window.location.href = "{{ url('review/display').'/'.$reviewMeetingIns->id }}";
                    });
                }
            });
        });
    });
</script>