@extends('layouts.app')


@section('HTMLContent')
    <div class="list-group">
        <?php $i=1 ?>
        @foreach($reviewMeetings as $reviewMeeting)
            <div>
                <a href="{{ url('review/display/'.$reviewMeeting->id) }}" target="_blank"
                   class="list-group-item stage-{{ $reviewMeeting->stage }}">
                    {{ $i++ }}. {{ $reviewMeeting->date }}&nbsp;{{ $reviewMeeting->time }}&nbsp;{{ $reviewMeeting->venue }}
                </a>
                @if($removable)
                    <button class="btn btn-danger btn-xs" data-meeting-id="{{ $reviewMeeting->id }}"
                            data-toggle="modal" data-target="#confirm-remove-meeting-modal">
                        <span class="glyphicon glyphicon-remove"></span>
                    </button>
                @endif
            </div>
        @endforeach
    </div>

    <div id="confirm-remove-meeting-modal" class="modal fade">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" type="button" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">确认删除采购评审会议?</h4>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" data-dismiss="modal" id="confirm-remove-meeting-button">确认</button>
                    <button class="btn btn-default" data-dismiss="modal">取消</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('javascriptContent')
<script type="text/javascript">
    $(document).ready(function () {

        var meetingID;

        $('#confirm-remove-meeting-modal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var meetingInfo = button.parent().find('a').html();
            meetingID = button.data('meeting-id');
            $(this).find('div.modal-body').html(meetingInfo);
        }).on('hide.bs.modal', function () {
            $(this).find('div.modal-body').html('');
        });

        $('#confirm-remove-meeting-button').click(function () {
            $.ajax({
                headers: headers,
                url: "{{ route('ReviewRemove') }}",
                data: {meetingID: meetingID},
                type: 'POST',
                success: function (data) {
                    handleReturn(data);
                }
            });
        });
    });
</script>
@endsection