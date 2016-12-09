@extends('review.display.function.frame')


@section('stageFunction')

    @foreach($topics as $topic)
        <div id="topic-{{ $topic->id }}" data-topic-id="{{ $topic->id }}" class="topic-info">
            <h4 class="project-info">
                {{ $topic->topicable->name }}: {{ $procurementMethods[$topic->topicable->approach] }}
            </h4>

            <div class="container">
                <div class="col-md-2"></div>
                <div class="col-md-2">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#decide-procurement-mode-modal"
                            data-operation="approve">通过</button>
                </div>
                <div class="col-md-2"></div>
                <div class="col-md-2">
                    <button class="btn btn-danger" data-toggle="modal" data-target="#decide-procurement-mode-modal"
                            data-operation="reject">退回</button>
                </div>
                <div class="col-md-2"></div>
            </div>
        </div>
    @endforeach


    <div id="decide-procurement-mode-modal" class="modal fade">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" type="button" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <br>
                    <div class="form-group">
                        <label>填写意见:</label>
                        <textarea class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal" id="decide-button">提交</button>
                    <button class="btn btn-default" data-dismiss="modal">取消</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
<script type="text/javascript">
    $(document).ready(function () {
        var topicID;
        var operation;

        $('#decide-procurement-mode-modal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            operation = button.data('operation');
            var topicInfoDiv = button.parents('div.topic-info')
            topicID = topicInfoDiv.data('topic-id');

            if ('approve' == operation) {
                $(this).find('h4.modal-title').html('确认通过');
                $('#decide-button').addClass('btn-primary');
            } else if ('reject' == operation) {
                $(this).find('h4.modal-title').html('确认退回');
                $('#decide-button').addClass('btn-danger');
            }

            var projectInfo = topicInfoDiv.find('h4.project-info').html();
            $(this).find('div.modal-body').prepend($('<h4>', {
                text: projectInfo
            }));

        }).on('hide.bs.modal', function () {
            $(this).find('h4.modal-title').html('');
            $(this).find('div.modal-body').find('h4').remove();
            $('#decide-button').removeClass('btn-primary').removeClass('btn-danger');
        });

        $('#decide-button').click(function () {
            $.ajax({
                headers: headers,
                url: "{{ route('ReviewStageDecideMode') }}",
                data: {
                    reviewMeetingID: reviewID,
                    topicID: topicID,
                    operation: operation,
                    comment: $(this).parents('div.modal-content').find('textarea').val()
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