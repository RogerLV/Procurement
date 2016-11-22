@extends('layouts.app')


@section('CSSContent')
<style type="text/css">
    button.page-link {
        height: 80px;
        width: 80px;
        border-radius: 10px;
        border: 1px solid #bce8f1;
        background-color: #d9edf7;
    }

    button.page-link > .glyphicon {
        font-size: 40px;
        color: #31708f;
    }
</style>
@endsection


@section('HTMLContent')
    <h3>{{ $userInfo->uEngName }} {{ $userInfo->uCnName }}</h3>
    <h3>{{ $userDeptInfo->deptEngName }}</h3>
    <h3>{{ $userDeptInfo->deptCnName }}</h3>
    <br>

    <h4>请选择您在系统中的角色:</h4>
        <div class="list-group">
        @foreach($userRoles as $userRoleIns)
            @if($userRoleIns->id == $selectedMapID)
                <a href="#" class="list-group-item active">
            @else
                <a href="#" class="list-group-item role-selection" data-mapid="{{ $userRoleIns->id }}">
            @endif
                {{ $deptInfo[$userRoleIns->dept]->deptCnName }}: {{ \App\Logic\Role\RoleFactory::create($userRoleIns->roleID)->getRoleName() }}
            </a>
        @endforeach
        </div>
    <br>

    <h4>我的待办:</h4>
    <div class="list-group">
        @foreach($pendingProjects as $project)
            <a href="{{ url('project/display').'/'.$project->id }}" target="_blank"
                class="list-group-item list-group-item-info">
                <h5>{{ $project->name }}</h5>
            </a>
            <br>
        @endforeach
    </div>
    <br>

    <h4>您可以访问以下页面:</h4>
    @foreach($pages as $pageIns)
        <button target="_blank" class="page-link" onclick="window.open('{{ $pageIns->url }}')">
            <span class="glyphicon {{ $pageIns->icon }}"></span><br>
            {{ $pageIns->name }}
        </button>&nbsp;
    @endforeach

@endsection


@section('javascriptContent')
<script type="text/javascript">
    $(document).ready(function () {
        $('.role-selection').click(function () {
            var mapid = $(this).data('mapid');

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')
                },
                url: "{{ route(ROUTE_NAME_ROLE_SELECT) }}",
                data: {'mapid': mapid},
                type: 'POST',
                beforeSend: function () {
                    setAlertText('正在切换');
                    $('#alert-modal').modal('show');
                },
                success: function (data) {
                    $('#alert-modal').modal('hide');
                    handleReturn(data, function () {
                        window.location.reload();
                    });
                }
            });
        });
    });
</script>
@endsection