<div class="page-header">
    <h4>委员会传签结果</h4>
</div>

<table class="table">
    <tr><td colspan="4" align="center">{{ ROLE_NAME_REVIEW_COMMITTEE_MEMBER }}</td></tr>
    @foreach($fullCommittee->whereLoose('roleID', ROLE_ID_REVIEW_COMMITTEE_MEMBER) as $member)
        @include('project.display.stage.passsignresult.entry')
    @endforeach

    <tr><td colspan="4" align="center">{{ ROLE_NAME_REVIEW_VICE_DIRECTOR }}</td></tr>
    @foreach($fullCommittee->whereLoose('roleID', ROLE_ID_REVIEW_VICE_DIRECTOR) as $member)
        @include('project.display.stage.passsignresult.entry')
    @endforeach

    <tr><td colspan="4" align="center">{{ ROLE_NAME_REVIEW_DIRECTOR }}</td></tr>
    @foreach($fullCommittee->whereLoose('roleID', ROLE_ID_REVIEW_DIRECTOR) as $member)
        @include('project.display.stage.passsignresult.entry')
    @endforeach
</table>