<div class="page-header">
    <h4>邀请委员会委员</h4>
</div>
<?php $roleID = ROLE_ID_REVIEW_COMMITTEE_MEMBER?>
@foreach($member as $entry)
    @include('review.display.function.apply.inviteparticipant.entry')
@endforeach

<div class="page-header">
    <h4>邀请委员会副主任</h4>
</div>
<?php $roleID = ROLE_ID_REVIEW_VICE_DIRECTOR?>
@foreach($viceDirector as $entry)
    @include('review.display.function.apply.inviteparticipant.entry')
@endforeach

<div class="page-header">
    <h4>邀请委员会主任</h4>
</div>
<?php $roleID = ROLE_ID_REVIEW_DIRECTOR?>
@foreach($director as $entry)
    @include('review.display.function.apply.inviteparticipant.entry')
@endforeach

<div class="page-header">
    <h4>特邀列席<small>optional</small></h4>
</div>
<?php $roleID = ROLE_ID_SPECIAL_INVITE?>
@foreach($specialInvites as $entry)
    @include('review.display.function.apply.inviteparticipant.entry')
@endforeach


<script type="text/javascript">
    $(document).ready(function () {
        $('input.invite-participants').change(function () {
            $.ajax({
                headers: headers,
                url: "{{ route(ROUTE_NAME_REVIEW_PARTICIPANT_EDIT) }}",
                data: {
                    reviewMeetingID: "{{ $reviewMeetingIns->id }}",
                    lanID: $(this).val(),
                    operation: this.checked ? 'add' : 'remove',
                    roleID: $(this).data('role-id')
                },
                type: 'POST',
                success: function (data) {
                    handleReturn(data, function () {
                        // do nothing
                    });
                }
            });
        });
    });
</script>