@extends('project/display/function/frame')


@section('stageFunction')
    <p style="color:red"><i>*尽职调查提交后将不能更改。</i></p>
    {{--insert style code here--}}
    <style type="text/css">
        div.request-entry > div.input-group > span.input-group-btn {
            vertical-align:top
        }

        div.request-entry > div.input-group > span.input-group-btn > button {
            height: 74px;
        }
    </style>
    @foreach($dueDiligence as $entry)
        <div class="request-entry">
            <br>
            <label>{{ $entry->request }}</label>
            <div class="input-group">
                @if(is_null($entry->answer))
                    <textarea class="form-control" rows="3"></textarea>
                @else
                    <textarea class="form-control" rows="3" readonly>{{ $entry->answer }}</textarea>
                @endif
                <span class="input-group-btn">
                    @if(is_null($entry->answer))
                        <button class="btn btn-primary" data-toggle="modal" data-request-id="{{ $entry->id }}"
                                data-target="#confirm-answer-request-modal">
                            提交
                        </button>
                    @else
                        <button class="btn btn-primary" disabled>提交</button>
                    @endif
                </span>
            </div>
        </div>
    @endforeach

    <div id="confirm-answer-request-modal" class="modal fade">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" type="button" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">确认提交回答</h4>
                </div>
                <div class="modal-body">
                    <p style="color:red"><i>*尽职调查提交后将不能更改。</i></p>
                    <div id="request-content"></div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" id="confirm-answer-request-button" data-dismiss="modal">提交</button>
                    <button class="btn btn-default" data-dismiss="modal">取消</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
<script type="text/javascript">
    $(document).ready(function () {
        var requestID, button, textarea, answer;
        $('#confirm-answer-request-modal').on('show.bs.modal', function (event) {
            button = $(event.relatedTarget);
            var container = button.parents('div.request-entry');
            textarea = container.find('textarea');
            requestID = button.data('request-id');

            var request = container.find('label').html();
            answer = textarea.val();

            if (0 == answer.length) {
                event.preventDefault();
                setAlertText('答复内容不能为空');
                $('#alert-modal').modal('show');
                return;
            }

            $('#request-content').append($('<h4/>', {
                text: request
            })).append($('<p/>', {
                text: answer
            }));
        }).on('hide.bs.modal', function () {
            $('#request-content').empty();
        });

        $('#confirm-answer-request-button').click(function () {
            $.ajax({
                headers: headers,
                url: "{{ route(ROUTE_NAME_DUE_DILIGENCE_ANSWER) }}",
                data: {
                    projectid: projectID,
                    requestid: requestID,
                    answer: answer
                },
                type: 'POST',
                success: function (data) {
                    handleReturn(data, function () {
                        button.attr('disabled', true);
                        textarea.attr('readonly', true);
                    });
                }
            });
        });
    });
</script>
@endsection