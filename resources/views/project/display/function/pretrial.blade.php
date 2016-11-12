@extends('project/display/function/frame')


@section('stageFunction')
    <table>
        <tr>
            <td><h4>采购方式:</h4></td>
            <td>{{ $project->getProcurment() }}</td>
        </tr>
        @if($project->involveReview)
            <tr>
                <td><h4>采购方式申请报告:</h4></td>
                <td><iframe src="{{ $procurementMethodReport->getUrl() }}" allowfullscreen width="700px" height="500px"></iframe></td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <p><a href="{{ $procurementMethodReport->getUrl() }}" target="iframe_a">打开浏览</a></p>
                </td>
            </tr>
        @endif
    </table>

    <div class="container">
        <div class="col-md-2"></div>
        <div class="col-md-2">
            <button class="btn btn-primary" data-toggle="modal" data-target="#pretrail-approve-modal"
                    data-operation="approve">通过</button>
        </div>
        <div class="col-md-2"></div>
        <div class="col-md-2">
            <button class="btn btn-danger" data-toggle="modal" data-target="#pretrail-reject-modal"
                    data-operation="reject">退回</button>
        </div>
        <div class="col-md-2"></div>
    </div>

    <div id="pretrail-approve-modal" class="modal fade">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" type="button" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">确认通过</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="secretariat-comments">秘书组意见:</label>
                        <textarea class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" id="pretrail-approve-button" data-dismiss="modal">通过</button>
                    <button class="btn btn-default" data-dismiss="modal">取消</button>
                </div>
            </div>
        </div>
    </div>

    <div id="pretrail-reject-modal" class="modal fade">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" type="button" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">确认退回</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="secretariat-comments">秘书组意见:</label>
                        <textarea class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" id="pretrail-reject-button" data-dismiss="modal">退回</button>
                    <button class="btn btn-default" data-dismiss="modal">取消</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
<script type="text/javascript">
    $(document).ready(function () {

        var sendPretrailResult = function (operation, comment) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')
                },
                url: "{{ route(ROUTE_NAME_STAGE_PRETRIAL) }}",
                data: {
                    operation: operation,
                    comment: comment,
                    projectid: projectID
                },
                type: 'POST',
                success: function (data) {
                    handleReturn(data);
                }
            });
        };

        $('#pretrail-approve-button').click(function () {
            $comment = $(this).parents('div.modal-content').find('textarea').val();
            sendPretrailResult('approve', $comment);
        });

        $('#pretrail-reject-button').click(function () {
            $comment = $(this).parents('div.modal-content').find('textarea').val();
            sendPretrailResult('reject', $comment);
        });
    });
</script>
@endsection