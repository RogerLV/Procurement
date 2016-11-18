@extends('project/display/function/frame')


@section('stageFunction')
    @include('documentsingleupload')

    @if($showFinishButton)
        <button class="btn btn-primary btn-block" id="complete-file-contract">
            完成
        </button>
    @endif
@endsection


@section('script')
<script type="text/javascript">
    $(document).ready(function () {
        $('#complete-file-contract').click(function () {
            $.ajax({
                headers: headers,
                url: "{{ route(ROUTE_NAME_STAGE_COMPLETE) }}",
                data: {projectid: projectID},
                type: 'POST',
                success: function (data) {
                    handleReturn(data);
                }
            });
        });
    });
</script>
@endsection