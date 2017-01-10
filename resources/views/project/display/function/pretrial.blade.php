@extends('project/display/function/frame')


@section('stageFunction')
    <table>
        <tr>
            <td><h4>采购方式:</h4></td>
            <td>{{ $project->getProcurement() }}</td>
        </tr>

        <tr>
            <td><h4>采购方式申请报告:</h4></td>
            <td><iframe src="{{ $procurementMethodReport->getUrl() }}" allowfullscreen width="700px" height="500px"></iframe></td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <p><a href="{{ $procurementMethodReport->getUrl() }}" target="iframe_a">打开浏览</a></p>
            </td>
        </tr>
    </table>

    <div class="container">
        <div class="col-md-2"></div>
        <div class="col-md-2">
            <button class="btn btn-primary" data-toggle="modal" data-target="#approve-modal">通过</button>
        </div>
        <div class="col-md-2"></div>
        <div class="col-md-2">
            <button class="btn btn-danger" data-toggle="modal" data-target="#reject-modal">退回</button>
        </div>
        <div class="col-md-2"></div>
    </div>

    <div id="approve-modal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" type="button" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">确认通过</h4>
                </div>
                <div class="modal-body">
                    <div class="checkbox">
                        <label><input type="checkbox" id="skip-pass-sign"><b>不需要传签</b></label>
                    </div>

                    <div class="form-group">
                        <ul>
                            <li style="color: red"><i>对于采购方式已经过管理层批准的,无需经过委员会传签。</i></li>
                            <li style="color: red"><i>如果选择不传签,请在意见中详细说明原因。</i></li>
                        </ul>
                        <label>填写意见:</label>
                        <textarea class="form-control" id="approve-opinion" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" id="approve-button" data-dismiss="modal">通过</button>
                    <button class="btn btn-default" data-dismiss="modal">取消</button>
                </div>
            </div>
        </div>
    </div>

    <div id="reject-modal" class="modal fade">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" type="button" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">确认退回</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>填写意见:</label>
                        <textarea class="form-control" id="reject-opinion" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" id="reject-button" data-dismiss="modal">退回</button>
                    <button class="btn btn-default" data-dismiss="modal">取消</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {

            $('#reject-button').click(function () {
                $.ajax({
                    headers: headers,
                    url: "{{ route(ROUTE_NAME_STAGE_APPROVE) }}",
                    data: {
                        operation: 'reject',
                        comment: $('#reject-opinion').val(),
                        projectid: projectID
                    },
                    type: 'POST',
                    success: function (data) {
                        handleReturn(data);
                    }
                });
            });

            $('#approve-button').click(function () {
                $.ajax({
                    headers: headers,
                    url: "{{ route('StagePretrial') }}",
                    data: {
                        comment: $('#approve-opinion').val(),
                        skipPassSign: $('#skip-pass-sign').is(':checked'),
                        projectid: projectID
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