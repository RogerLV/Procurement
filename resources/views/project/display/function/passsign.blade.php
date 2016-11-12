@extends('project/display/function/frame')


@section('stageFunction')
    <table class="table">
        @foreach($fullCommittee as $member)
            <tr>
                <td>{{ $member->department->deptCnName }}</td>
                <td>{{ $member->user->getTriName() }}</td>
                <td align="left">
                    @if($signs->has($member->lanID))
                        {{ $passSignValues[$signs->get($member->lanID)->data1] }}
                    @else
                        未操作
                    @endif
                </td>
            </tr>
        @endforeach
    </table>

    @if($signable)
    <div class="container">
        <div class="col-md-2"></div>
        <div class="col-md-2">
            <button class="btn btn-primary" data-toggle="modal" data-target="#passsign-approve-modal"
                    data-operation="approve">同意</button>
        </div>
        <div class="col-md-2"></div>
        <div class="col-md-2">
            <button class="btn btn-danger" data-toggle="modal" data-target="#passsign-reject-modal"
                    data-operation="reject">不同意</button>
        </div>
        <div class="col-md-2"></div>
    </div>

    <div id="passsign-approve-modal" class="modal fade">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" type="button" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">同意</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>评审委员意见:</label>
                        <textarea class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary passsign-commit-button" data-operation="approve" data-dismiss="modal">同意</button>
                    <button class="btn btn-default" data-dismiss="modal">取消</button>
                </div>
            </div>
        </div>
    </div>

    <div id="passsign-reject-modal" class="modal fade">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" type="button" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">不同意</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>评审委员意见:</label>
                        <textarea class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger passsign-commit-button" data-operation="reject" data-dismiss="modal">不同意</button>
                    <button class="btn btn-default" data-dismiss="modal">取消</button>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection


@section('script')
<script type="text/javascript">
    $(document).ready(function () {

        $('button.passsign-commit-button').click( function () {

            var comment = $(this).parents('div.modal-content').find('textarea').val();
            var operation = $(this).data('operation');

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')
                },
                url: "{{ route(ROUTE_NAME_STAGE_PASS_SIGN) }}",
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
        });
    });
</script>
@endsection