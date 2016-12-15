<div class="checkbox">
    <label>
        <input class="invite-participants" type="checkbox" value="{{ $entry->lanID }}"
               data-role-id="{{ $entry->roleID }}"
               @if(!$invited->where('lanID', $entry->lanID)->whereLoose('roleID', $roleID)->isEmpty())
                checked
               @endif
        >
        {{ $entry->user->department->deptCnName." ".$entry->user->getTriName() }}
    </label>
</div>