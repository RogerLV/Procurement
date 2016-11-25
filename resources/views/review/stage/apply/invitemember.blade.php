<div class="page-header">
    <h4>邀请委员</h4>
</div>
@foreach($committee as $member)
    <div class="checkbox">
        <label>
            <input class="invite-participants" type="checkbox" value="{{ $member->lanID }}" data-role-id="{{ $member->roleID }}"
            @if(0 != $invited->where('lanID', $member->lanID)->where('roleID', $member->roleID)->count())
                checked
            @endif
            >
            {{ $member->user->getTriName() }}
        </label>
    </div>
@endforeach

<div class="page-header">
    <h4>特邀列席<small>optional</small></h4>
</div>
@foreach($specialInvites as $invitee)
    <div class="checkbox">
        <label>
            <input class="invite-participants" type="checkbox" value="{{ $invitee->lanID }}" data-role-id="{{ $invitee->roleID }}"
                   @if(0 != $invited->where('lanID', $invitee->lanID)->where('roleID', $invitee->roleID)->count())
                   checked
                    @endif
            >
            {{ $invitee->user->getTriName() }}
        </label>
    </div>
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
                    })
                }
            });
        });
    });
</script>