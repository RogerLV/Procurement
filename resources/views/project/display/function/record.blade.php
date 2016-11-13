@extends('project/display/function/frame')


@section('stageFunction')
    @foreach($renderDocType as $docTypeIns)
        @include('documentsingleupload', ['docTypeIns' => $docTypeIns])
    @endforeach

    @if($showFinishButton)
        <div class="container outer">
            <button class="btn btn-primary" id="finish-record">完成</button>
        </div>
    @endif

@endsection


@section('script')
<script type="text/javascript">
    $(document).ready(function () {
        @if($showFinishButton)
            $('#finish-record').click(function () {
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