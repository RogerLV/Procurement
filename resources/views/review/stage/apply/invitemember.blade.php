<div class="page-header">
    <h4>邀请委员</h4>
</div>
@foreach($committee as $member)
    <div class="checkbox">
        <label><input class="invite-member" type="checkbox" value="{{ $member->lanID }}">{{ $member->user->getTriName() }}</label>
    </div>
@endforeach


<script type="text/javascript">
    $(document).ready(function () {
        $('input.invite-member').change(function () {
            $.ajax({
                headers: headers,
                url: "{{ route(ROUTE_NAME_REVIEW_PARTICIPANT_EDIT) }}",
                data: {
                    reviewMeetingID: "{{ $reviewMeetingIns->id }}",
                    lanID: $(this).val(),
                    operation: this.checked ? 'add' : 'remove'
                },
                type: 'POST',
                success: function (data) {
                    handleReturn(data, function () {
                        // do nothing
                    })
                }
            });
        });
    });
</script>