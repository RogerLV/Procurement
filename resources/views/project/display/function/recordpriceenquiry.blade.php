@extends('project/display/function/frame')


@section('stageFunction')
    <div class="form-group">
        <label>项目询价函:</label>
        @if(in_array(DOC_TYPE_PROJECT_INQUIRY, $uploadedTypes))
            <span class="glyphicon glyphicon-ok" style="color:green"></span>
        @endif
        <input type="file" id="upload-project-enquiry" name="upload-doc">
    </div>

    @if($uploadReviewReport)
        <div class="form-group">
            <label>上会报告:</label>
            @if(in_array(DOC_TYPE_REVIEW_REPORT, $uploadedTypes))
                <span class="glyphicon glyphicon-ok" style="color:green"></span>
            @endif
            <input type="file" id="upload-review-report" name="upload-doc">
        </div>
    @endif

    @if($showFinishButton)
        <div class="container outer">
            <button class="btn btn-primary" id="finish-price-enquiry">完成</button>
        </div>
    @endif

@endsection


@section('script')
<script type="text/javascript">
    $(document).ready(function () {
        $('#upload-project-enquiry').fileinput({
            uploadUrl: "{{ route(ROUTE_NAME_DOCUMENT_UPLOAD) }}",
            dropZoneEnabled: false,
            language:'zh',
            maxFileCount: 1,
            uploadExtraData: {
                reference: "Projects",
                id: projectID,
                filetype: "{{ DOC_TYPE_PROJECT_INQUIRY }}",
                _token: $("meta[name='csrf-token']").attr('content')
            }
        }).on('fileuploaded', function (event, data) {
            handleReturn(data.response);
        });

        @if($uploadReviewReport)
        $('#upload-review-report').fileinput({
            uploadUrl: "{{ route(ROUTE_NAME_DOCUMENT_UPLOAD) }}",
            dropZoneEnabled: false,
            language:'zh',
            maxFileCount: 1,
            uploadExtraData: {
                reference: "Projects",
                id: projectID,
                filetype: "{{ DOC_TYPE_REVIEW_REPORT }}",
                _token: $("meta[name='csrf-token']").attr('content')
            }
        }).on('fileuploaded', function (event, data) {
            handleReturn(data.response);
        });
        @endif

        @if($showFinishButton)
        $('#finish-price-enquiry').click(function () {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')
                },
                url: "{{ route(ROUTE_NAME_STAGE_FINISH_RECORD) }}",
                data: {projectid: projectID},
                type: 'POST',
                success: function (data) {
                    handleReturn(data);
                }
            });
        });
        @endif
    });
</script>
@endsection