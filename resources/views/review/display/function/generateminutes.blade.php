@extends('review.display.function.frame')


@section('stageFunction')
    <form id="meeting-minutes-meta-info">
        <div class="form-group required">
            <label>抬头</label>
            <input type="text" class="form-control" data-attr="header" value="{{ $metaInfo->header or null }}" required>
        </div>

        <div class="form-group required">
            <label>日期</label>
            <input type="text" class="form-control" data-attr="date" value="{{ $metaInfo->date or $reviewIns->date }}" required>
        </div>

        <div class="form-group required">
            <label>时间</label>
            <input type="text" class="form-control" data-attr="time" value="{{ $metaInfo->time or $reviewIns->time }}" required>
        </div>

        <div class="form-group required">
            <label>地点</label>
            <input type="text" class="form-control" data-attr="venue" value="{{ $metaInfo->venue or $reviewIns->venue }}" required>
        </div>

        <div class="form-group required">
            <label>主持</label>
            <input type="text" class="form-control" data-attr="host" value="{{ $metaInfo->host or null }}" required>
        </div>

        <div class="form-group required">
            <label>列席</label>
            <input type="text" class="form-control" data-attr="attendance" value="{{ $metaInfo->attendance or null }}" required>
        </div>

        <div class="form-group required">
            <label>记录</label>
            <input type="text" class="form-control" data-attr="recorder" value="{{ $metaInfo->recorder or null }}" required>
        </div>

        <div class="form-group required">
            <label>行内发送</label>
            <input type="text" class="form-control" data-attr="sendTo" value="{{ $metaInfo->sendTo or null }}" required>
        </div>

        <div class="form-group required">
            <label>注脚</label>
            <input type="text" class="form-control" data-attr="footer"
                   value="{{ $metaInfo->footer or '中国银行新加坡分行采购评审委员会'.' '.date('Y-m-d') }}" required>
        </div>

        <input type="submit" class="btn btn-block btn-primary">
    </form>

    @foreach($topics as $idx => $topic)
        <div class="page-header">
            <h5>议题{{ $idx+1 }}: {{ $topic->topicable->name }}</h5>
            <div class="input-group">
                @if(isset($topic->meetingMinutesContent))
                    <?php $content = preg_replace('/<br\s?\/?>/i', "\r", $topic->meetingMinutesContent->content);?>
                    <textarea class="form-control" rows="10">{{ $content }}</textarea>
                @else
                    <textarea class="form-control" rows="10" placeholder="请填写纪要"></textarea>
                @endif
                <span class="input-group-btn" style="vertical-align:top;">
                    <button class="btn btn-primary submit-meeting-minutes-content"
                            data-topic-id="{{ $topic->id }}" style="height: 214px; width: 60px">
                        提交
                    </button>
                </span>
            </div>
        </div>
    @endforeach

    <div class="outer">
        <button class="btn btn-lg btn-primary" data-toggle="modal"
                data-target="#confirm-finish-generating-meeting-minutes">完成编辑</button>
    </div>

    <div id="confirm-finish-generating-meeting-minutes" class="modal fade">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" type="button" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">确定完成编辑?</h4>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" id="confirm-finish-generating-button" data-dismiss="modal">确定</button>
                    <button class="btn btn-default" data-dismiss="modal">取消</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script type="text/javascript">
    $(document).ready(function () {
        $('#meeting-minutes-meta-info').submit(function (e) {
            e.preventDefault();

            var data = {};
            $(this).find('input:text').each(function () {
                var attr = $(this).data('attr');
                data[attr] = $(this).val();
            });
            data.reviewMeetingID = reviewID;

            $.ajax({
                headers: headers,
                url: "{{ route('MeetingMinutesAddMeta') }}",
                data: data,
                type: 'POST',
                success: function (data) {
                    handleReturn(data, function () {
                        setAlertText('采购评审信息添加成功。');
                        $('#alert-modal').modal('show');
                    })
                }
            });
        });

        $('button.submit-meeting-minutes-content').click(function () {
            var content = $(this).parents('div.input-group').find('textarea').val();
            if (0 == content.length) {
                setAlertText('纪要内容不能为空。');
                $('#alert-modal').modal('show');
                return;
            }

            $.ajax({
                headers: headers,
                url: "{{ route('MeetingMinutesAddContent') }}",
                data: {
                    content: content,
                    topicID: $(this).data('topic-id')
                },
                type: 'POST',
                success: function(data) {
                    handleReturn(data, function () {
                        setAlertText('纪要添加成功。');
                        $('#alert-modal').modal('show');
                    });
                }
            });
        });

        $('#confirm-finish-generating-button').click(function () {
            $.ajax({
                headers: headers,
                url: "{{ route('ReviewStageComplete') }}",
                data: {reviewMeetingID: reviewID},
                type: 'POST',
                success: function (data) {
                    handleReturn(data);
                }
            });
        });
    });
</script>
@endsection