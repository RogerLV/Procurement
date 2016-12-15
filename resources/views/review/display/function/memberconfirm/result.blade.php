<table class="table table-bordered">
    <tr><td colspan="4" align="center"><strong>{{ ROLE_NAME_REVIEW_COMMITTEE_MEMBER }}</strong></td></tr>
    @foreach($participants->whereLoose('roleID', ROLE_ID_REVIEW_COMMITTEE_MEMBER) as $participant)
        @include('review.display.function.memberconfirm.entry')
    @endforeach

    <tr><td colspan="4" align="center"><strong>{{ ROLE_NAME_REVIEW_VICE_DIRECTOR }}</strong></td></tr>
    @foreach($participants->whereLoose('roleID', ROLE_ID_REVIEW_VICE_DIRECTOR) as $participant)
        @include('review.display.function.memberconfirm.entry')
    @endforeach

    <tr><td colspan="4" align="center"><strong>{{ ROLE_NAME_REVIEW_DIRECTOR }}</strong></td></tr>
    @foreach($participants->whereLoose('roleID', ROLE_ID_REVIEW_DIRECTOR) as $participant)
        @include('review.display.function.memberconfirm.entry')
    @endforeach

    <tr><td colspan="4" align="center"><strong>{{ ROLE_NAME_SPECIAL_INVITE }}</strong></td></tr>
    @foreach($participants->whereLoose('roleID', ROLE_ID_SPECIAL_INVITE) as $participant)
        @include('review.display.function.memberconfirm.entry')
    @endforeach
</table>