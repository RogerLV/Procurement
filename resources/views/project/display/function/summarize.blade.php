@extends('project/display/function/frame')


@section('stageFunction')
    <div class="form-group">
        <label for="project-summery">填写总结:</label>
        <textarea class="form-control" id="project-summery" rows="5"></textarea>
    </div>
    <button class="btn btn-primary btn-block" id="submit-project-summery">
        提交
    </button>
@endsection


@section('script')
<script type="text/javascript">
    $(document).ready(function () {
        $('#submit-project-summery').click(function () {
            var summary = $('#project-summery').val();
            if (0 == summary.length) {
                setAlertText('总结内容不能为空。');
                $('#alert-modal').modal('show');
                return;
            }

            $.ajax({
                headers: headers,
                url: "{{ route(ROUTE_NAME_STAGE_SUMMARIZE) }}",
                data: {
                    projectid: projectID,
                    summary: summary
                },
                type: 'POST',
                success: function (data) {
                    handleReturn(data);
                }
            });
        });
    });
</script>
@endsection