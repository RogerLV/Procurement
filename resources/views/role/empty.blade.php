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

@endsection


@section('javascriptContent')
<script type="text/javascript">
    $(document).ready(function () {
        $('#role-name-selector').change(function () {
            window.location.href = "{{ route(ROUTE_NAME_ROLE_LIST) }}"+"?roleid="+$(this).val();
        });
    });
</script>
@endsection