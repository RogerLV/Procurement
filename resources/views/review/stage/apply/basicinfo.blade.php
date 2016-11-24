<div class="form-group required">
    <label>日期</label>
    <input type="text" class="form-control review-meeting-info" data-attr="date" value="{{ $reviewMeetingIns->date }}">
</div>

<div class="form-group required">
    <label>时间</label>
    <input type="text" class="form-control review-meeting-info" data-attr="time" value="{{ $reviewMeetingIns->time }}">
</div>

<div class="form-group required">
    <label>地点</label>
    <input type="text" class="form-control review-meeting-info" data-attr="venue" value="{{ $reviewMeetingIns->venue }}">
</div>

<script type="text/javascript">
    $(document).ready(function () {

        $('input.review-meeting-info').blur(function () {
            var attr = $(this).data('attr');
            var val = $.trim($(this).val());

            if (val.length != 0) {
                $.ajax({
                    headers: headers,
                    url: "{{ route(ROUTE_NAME_REVIEW_EDIT) }}",
                    data: {
                        reviewMeetingID: "{{ $reviewMeetingIns->id }}",
                        attr: attr,
                        value: val
                    },
                    type: "POST",
                    success: function (data) {
                        handleReturn(data, function () {
                            // do nothing
                        });
                    }
                });
            }
        });
    });
</script>