@extends('layouts.app')


@section('HTMLContent')
    <div class="form-group">
        <label for="role-name-selector">请选择角色名称:</label>
        <select class="form-control" id="role-name-selector">
            <option disabled selected></option>
            @foreach($roleOptions as $roleID => $roleIns)
                <option value="{{ $roleID }}">{{ $roleIns->getRoleName() }}</option>
            @endforeach
        </select>
    </div>
    <br>

    <table class="table">
        <thead>
            <tr>
                <td>#</td>
                <td>姓名</td>
                <td>分机号</td>
                <td>部门</td>
                @if($editable)
                    <td>删除</td>
                @endif
            </tr>
        </thead>
        <tbody>
            <?php $i=1 ?>
            @forelse($roleEntries as $entry)
                <?php $user = $userInfo->get($entry->lanID) ?>
                <tr data-mapid="{{ $entry->id }}">
                    <td>{{ $i++ }}</td>
                    <td>{{ $user->uEngName}} {{ $user->uCnName }}</td>
                    <td>{{ $user->IpPhone }}</td>
                    <td>{{ $deptInfo->get($entry->dept)->deptCnName }}</td>
                    @if($editable)
                        <td>
                            <button class="btn btn-danger btn-xs remove-map" data-toggle="modal"
                                    data-target="#remove-modal">
                                <span class="glyphicon glyphicon-remove"></span>
                            </button>
                        </td>
                    @endif
                </tr>
            @empty
            @endforelse
            @if($editable)
                <tr>
                    <td colspan="100%" align="center">
                        <button class="btn btn-primary btn-xs" data-toggle="modal"
                                data-target="#add-modal">
                            <span class="glyphicon glyphicon-plus"></span>
                        </button>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    @if($editable)
        <div id="remove-modal" class="modal fade">
            <div class="modal-dialog modal-sm">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">确认删除?</h4>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" id="remove-map-button">删除</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    </div>
                </div>

            </div>
        </div>

        <div id="add-modal" class="modal fade">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">新增角色: {{ $selectedRole->getRoleName() }}</h4>
                    </div>
                    <div class="modal-body">
                        @if($selectedRole->assignDept())
                            <div class="form-group">
                                <label for="dept-value" class="control-label">选择部门</label>
                                <select id="dept-value" class="form-control" disabled>
                                    <option selected></option>
                                    @foreach($deptInfo as $deptIns)
                                        <option value="{{ $deptIns->dept }}">
                                            {{ $deptIns->deptCnName }} {{ $deptIns->deptEngName }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <input type="hidden" id="dept-value">
                        @endif
                        <div class="form-group">
                            <label for="candidate-selector" class="control-label">选择员工:</label>
                            <select id="candidate-selector" class="form-control">
                                <option disabled selected></option>
                                @foreach($selectedRole->getCandidates() as $candidate)
                                    <option value="{{ $candidate->lanID }}" data-dept="{{ $candidate->dept }}">
                                        {{ $candidate->uEngName }} {{ $candidate->uCnName }} {{ $candidate->IpPhone }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal" id="add-map-button">添加</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection


@section('javascriptContent')
    <script type="text/javascript">
        $(document).ready(function () {

            $('#role-name-selector').val({{ $selectedRole->getRoleID() }}).change(function () {
                window.location.href = "{{ route(ROUTE_NAME_ROLE_LIST) }}"+"?roleid="+$(this).val();
            });

            var mapid;

            $('button.remove-map').click(function() {
                mapid = $(this).parents('tr').data('mapid');
            });

            $('#remove-map-button').click(function() {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')
                    },
                    url: "{{ route(ROUTE_NAME_ROLE_REMOVE) }}",
                    data: {mapid: mapid},
                    type: 'POST',
                    beforeSend: function() {
                        setAlertText('正在删除');
                        $('#alert-modal').modal('show');
                    },
                    success: function (data) {
                        $('#alert-modal').modal('hide');
                        handleReturn(data, function () {
                            $('tr[data-mapid="'+mapid+'"]').hide();
                        });
                    }
                });
            });

            $('#candidate-selector').change(function () {
                var dept = $(this).find('option:selected').data('dept');
                $('#dept-value').val(dept).prop('disabled', false);
            });

            $('#add-map-button').click(function () {
                var lanID = $('#candidate-selector').val();
                var dept = $('#dept-value').val();

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')
                    },
                    url: '{{ route(ROUTE_NAME_ROLE_ADD) }}',
                    data: {
                        lanid: lanID,
                        dept: dept,
                        roleid: '{{ $selectedRole->getRoleID() }}',
                    },
                    type: 'POST',
                    beforeSend: function () {
                        setAlertText('正在添加');
                        $('#alert-modal').modal('show');
                    },
                    success: function (data) {
                        handleReturn(data);
                    }
                });
            });
        });
    </script>
@endsection