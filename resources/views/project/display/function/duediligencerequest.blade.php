@extends('project/display/function/frame')


@section('stageFunction')
    <label>添加提问:</label>
    <div class="input-group">
        <input class="form-control" id="add-due-diligence-request-input">
        <span class="input-group-btn">
            <button class="btn btn-primary" data-toggle="modal" data-target="#confirm-add-request-modal">
                添加
            </button>
        </span>
    </div>

    <div id="confirm-add-request-modal" class="modal fade">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" type="button" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">确认发起提问</h4>
                </div>
                <div class="modal-body">
                    <p id="request-content"></p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" id="confirm-add-request-button" data-dismiss="modal">提交</button>
                    <button class="btn btn-default" data-dismiss="modal">取消</button>
                </div>
            </div>
        </div>
    </div>

    {{--insert style code here--}}
    <style type="text/css">
        #q-a-zone > div.request-entry > div.input-group > span.input-group-btn {
            vertical-align:top
        }

        #q-a-zone > div.request-entry > div.input-group > span.input-group-btn > button {
            height: 74px;
        }
    </style>

    <div id="q-a-zone">
        @foreach($dueDiligence as $entry)
            <div class="request-entry">
                <br>
                <label>{{ $entry->request }}</label>
                <div class="input-group">
                    <textarea class="form-control" rows="3" readonly>{{ $entry->answer }}</textarea>
                    <span class="input-group-btn">
                        <button class="btn btn-primary" disabled>
                            提交
                        </button>
                    </span>
                    <span class="input-group-btn">
                        @if(!is_null($entry->answer))
                            <button class="btn btn-danger remove-request" data-request-id="{{ $entry->id }}" disabled>
                                删除
                            </button>
                        @else
                            <button class="btn btn-danger remove-request" data-request-id="{{ $entry->id }}" >
                                删除
                            </button>
                        @endif
                    </span>
                </div>
            </div>
        @endforeach
    </div>

    <br>
    @include('documentsingleupload')

    @if($showFinishButton)
        <button class="btn btn-primary btn-block" id="complete-due-diligence">
            完成
        </button>
    @endif
@endsection


@section('script')
<script type="text/javascript">
    $(document).ready(function () {

        $('#complete-due-diligence').click(function () {
            $.ajax({
                headers: headers,
                url: '{{ route(ROUTE_NAME_STAGE_COMPLETE) }}',
                data: {projectid: projectID},
                type: "POST",
                success: function (data) {
                    handleReturn(data);
                }
            });
        });

        $('#confirm-add-request-modal').on('show.bs.modal', function (event) {
            var request = $('#add-due-diligence-request-input').val();
            if (0 == request.length) {
                setAlertText('提问内容不能为空。');
                $('#alert-modal').modal('show');
                event.preventDefault();
                return;
            }

            $('#request-content').html(request);
        }).on('hide.bs.modal', function () {
            $('#request-content').html('');
        });

        var removeRequest = function () {
            var requestID = $(this).data('request-id');
            var div = $(this).parents('div.request-entry');
            $.ajax({
                headers: headers,
                url: "{{ route(ROUTE_NAME_DUE_DILIGENCE_REMOVE_REQUEST) }}",
                data: {
                    projectid: projectID,
                    requestid: requestID
                },
                type: 'POST',
                success: function (data) {
                    handleReturn(data, function () {
                        div.hide();
                    });
                }
            });
        }

        $('button.remove-request').click(removeRequest);

        $('#confirm-add-request-button').click(function () {
            $.ajax({
                headers: headers,
                url: "{{ route(ROUTE_NAME_DUE_DILIGENCE_ADD_REQUEST) }}",
                data: {
                    projectid: projectID,
                    request: $('#add-due-diligence-request-input').val()
                },
                type: "POST",
                success: function (data) {
                    handleReturn(data, function () {
                        $('#q-a-zone').append($('<div/>', {
                            class: 'request-entry'
                        }).append($('<br>')).append($('<label/>', {
                            text: data.info.request
                        })).append($('<div/>', {
                            class: 'input-group'
                        }).append($('<textarea/>', {
                            class: 'form-control',
                            rows: 3
                        }).prop('readonly', true)).append($('<span/>', {
                            class: 'input-group-btn',
                        }).append($('<button/>', {
                            class: 'btn btn-primary',
                            text: '提交'
                        }).prop('disabled', true)).append($('<button/>', {
                            class: 'btn btn-danger',
                            text: '删除'
                        }).bind('click', removeRequest).data('request-id', data.info.id)))));
                    });
                }
            });
        });
    });
</script>
@endsection