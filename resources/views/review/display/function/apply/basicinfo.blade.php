<div class="form-group required">
    <label>日期</label>
    <input type="text" id="select-date" class="form-control" value="{{ $reviewMeetingIns->date }}">
</div>

<div class="form-group required">
    <label>时间</label>
    <input type="text" class="form-control review-meeting-info" data-attr="time" value="{{ $reviewMeetingIns->time }}">
</div>

<div class="form-group required">
    <label>地点</label>
    <input type="text" class="form-control review-meeting-info" data-attr="venue" value="{{ $reviewMeetingIns->venue }}">
</div>

<script type="text/javascript" src="{{ asset('js/jquery-ui.min.js') }}"></script>
<link href="{{ asset('css/jquery-ui.min.css') }}" media="all" rel="stylesheet" type="text/css" />

<script type="text/javascript">
    $(document).ready(function () {
        $('#select-date').datepicker({
            minDate: 0,
            dateFormat: "yy-mm-dd",
            onSelect: function (dateText) {
                commitInfo('date', dateText);
            }
        });

        $('input.review-meeting-info').blur(function () {
            var attr = $(this).data('attr');
            var val = $.trim($(this).val());

            if (val.length != 0) {
                commitInfo(attr, val);
            }
        });

        var commitInfo = function (attr, value) {
            $.ajax({
                headers: headers,
                url: "{{ route('ReviewEdit') }}",
                data: {
                    reviewMeetingID: "{{ $reviewMeetingIns->id }}",
                    attr: attr,
                    value: value
                },
                type: 'POST',
                success: function (data) {
                    handleReturn(data, function () {
                       // do nothing
                    });
                }
            });
        }
    });
</script>