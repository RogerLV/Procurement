@extends('project/display/function/frame')


@section('stageFunction')
    <form id="select-procurement-method" method="POST" enctype="multipart/form-data">
        <div class="checkbox">
            <label><input type="checkbox" name="select-from-vendor"><b>是否选型入围?</b></label>
        </div>

        <div class="form-group required">
            <label for="procurement-method-selector">选择采购方式:</label>
            <select class="form-control" required name="procurement-method-selector">
                <option selected disabled></option>
                @foreach($procurementMethods as $idx => $name)
                    <option value="{{ $idx }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>

        @if($uploadReport)
        <div class="form-group required">
            <label for="procurement-method-report">采购方式申请报告:</label>
            <input type="file" class="file-input" name="procurement-method-report" required>
        </div>
        @endif

        <div class="outer">
            <input class="btn btn-lg btn-primary" value="提交" type="submit">
        </div>
    </form>
@endsection


@section('script')
<script type="text/javascript">
    $(document).ready(function () {
        $('input.file-input').fileinput({
            language:'zh',
            showUpload: false,
            maxFileCount: 1,
        });

        $('#select-procurement-method').submit(function (event) {
            event.preventDefault();

            var form = new FormData();
            form.append('_token', $("meta[name='csrf-token']").attr('content'));
            form.append('select-from-vendor', $("input[name=select-from-vendor]").val());
            form.append('procurement-method', $("select[name=procurement-method-selector]").val());
            form.append('projectid', projectID);
            @if($uploadReport)
                form.append('procurement-method-report', $("input[name=procurement-method-report]")[0].files[0]);
            @endif

            var xhr = new XMLHttpRequest();
            xhr.open('POST', '{{ route(ROUTE_NAME_STAGE_SELECT_MODE) }}');
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.onload = function () {
                var data = JSON.parse(xhr.response);
                handleReturn(data);
            };

            xhr.send(form);
        });
    });
</script>
@endsection