@extends('project/display/function/frame')


@section('stageFunction')
    @foreach($renderDocType as $docTypeIns)
        @include('documentsingleupload', ['docTypeIns' => $docTypeIns])
    @endforeach

    @if($priceNegotiation)
        @include('project/display/function/pricenegotiation')
    @endif
    <br>

    @if('ScoreStageEditTemplate' == $scorePhase && $project->lanID == $userLanID)
        <button class="btn btn-info btn-block"
                onclick="location.href='{{ url('score/edittemplate') }}'+'/'+projectID">
            编辑评分模板
        </button>
    @elseif('ScoreStageMemberScoring' == $scorePhase && $project->roles->pluck('lanID')->contains($userLanID))
        <button class="btn btn-info btn-block"
                onclick="location.href='{{ url('score/page') }}'+'/'+projectID">
            评分
        </button>
    @elseif('ScoreStageMemberScoreComplete' == $scorePhase)
        <button class="btn btn-info btn-block"
                onclick="window.open('{{ url('score/overview') }}'+'/'+projectID, '_blank')">
            评分汇总
        </button>
    @endif
    <br>

    @if($showFinishButton)
        <button class="btn btn-primary btn-block" id="finish-record">完成</button>
    @endif

@endsection


@section('script')
<script type="text/javascript">
    $(document).ready(function () {
        @if($showFinishButton)
            $('#finish-record').click(function () {
                $.ajax({
                    headers: headers,
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