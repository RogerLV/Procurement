@extends('project/display/function/frame')


@section('stageFunction')
    {{--group member constrain table--}}
    <table class="table">
        <tbody>
            @foreach($memberDeptsWithRoles as $dept => $memberDept)
                <tr>
                    <td>{{ $deptInfo[$dept]->deptCnName }}</td>
                    <td>{{ $memberDept->memberAmount }}人</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if($assignable)
    <div class="input-group">
        <select class="form-control" id="project-maker-selector">
            <option disabled selected></option>
            @foreach($candidates as $candidate)
                <option value="{{ $candidate->lanID }}">{{ $candidate->getTriName() }}</option>
            @endforeach
        </select>
        <span class="input-group-btn">
            <button class="btn btn-primary" id="project-maker-add-btn">
                添加
            </button>
        </span>
    </div>
    @endif

    <table class="table">
        <tbody>

            @foreach($memberDeptsWithRoles as $memberDept)
                @if($memberDept->dept == $userDept)
                    <tr class="local"></tr>
                @endif
                @foreach($memberDept->role as $roleEntry)
                    <tr>
                        <td>{{ $deptInfo[$memberDept->dept]->deptCnName }}</td>
                        <td>{{ $userInfo[$roleEntry->lanID]->getTriName() }}</td>
                        @if($memberDept->dept == $userDept && $assignable)
                            <td>
                                <button class="btn btn-danger btn-xs remove-project-role"
                                        data-projectroleid="{{ $roleEntry->id }}">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </button>
                            </td>
                        @endif
                    </tr>
                @endforeach
            @endforeach

        </tbody>
    </table>
    @if($assignable)
        <div class="container outer">
            <button class="btn btn-primary" id="submit-assign-maker">完成</button>
        </div>
    @endif
@endsection


@section('script')
<script type="text/javascript">
    $(document).ready(function () {
        @if($assignable)
        $('#submit-assign-maker').click(function () {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')
                },
                url: "{{ route('StageAssignComplete') }}",
                data: {
                    projectid: projectID
                },
                type: 'POST',
                success: function (data) {
                    handleReturn(data);
                }
            });
        });

        var deptName = "{{ $deptInfo[$userDept]->deptCnName }}";
        var removeRole = function () {
            var projectRoleID = $(this).data('projectroleid');
            var row = $(this).parents('tr');

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')
                },
                url: "{{ route(ROUTE_NAME_ASSIGN_MAKER_REMOVE) }}",
                data: {
                    projectroleid: projectRoleID,
                    projectid: projectID
                },
                type: 'POST',
                success: function (data) {
                    handleReturn(data, function () {
                        row.hide();
                    });
                }
            });
        };

        $('button.remove-project-role').click(removeRole);

        $('#project-maker-add-btn').click(function () {
            var makerLanID = $('#project-maker-selector').val();
            var makerName = $('#project-maker-selector').find('option:selected').html();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')
                },
                url: "{{ route(ROUTE_NAME_ASSIGN_MAKER_ADD) }}",
                data: {
                    lanID: makerLanID,
                    projectDeptID: "{{ $memberDeptsWithRoles[$userDept]->id }}",
                    projectid: projectID
                },
                type: 'POST',
                success: function (data) {
                    handleReturn(data, function () {
                        $('<tr/>').append($('<td/>', {
                            text: deptName
                        })).append($('<td/>', {
                            text: makerName
                        })).append($('<td/>').append($('<button/>', {
                            class: 'btn btn-danger btn-xs',
                        }).data('lanid', makerLanID)
                        .data('projectroleid', data.info.projectRole.id)
                        .bind('click', removeRole)
                        .append($('<span/>', {
                            class: 'glyphicon glyphicon-remove'
                        })))).insertAfter('tr.local:last');
                    });
                }
            });
        });
        @endif
    });
</script>
@endsection