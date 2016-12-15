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

        <input type="submit" class="btn btn-block btn-primary" value="保存">
    </form>

    @foreach($topics as $idx => $topic)
        <div class="page-header">
            <h5>议题{{ $idx+1 }}: {{ $topic->topicable->name }}</h5>
            @if(isset($topic->meetingMinutesContent))
                <?php $content = preg_replace('/<br\s?\/?>/i', "\r", $topic->meetingMinutesContent->content);?>
                <textarea class="form-control topic-content" data-topic-id="{{ $topic->id }}" rows="10">{{ $content }}</textarea>
            @else
                <textarea class="form-control topic-content" data-topic-id="{{ $topic->id }}" rows="10" placeholder="请填写纪要"></textarea>
            @endif
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

        $('textarea.topic-content').blur(function () {
            var content = $(this).val();
            if (0 != $.trim(content).length) {
                $.ajax({
                    headers: headers,
                    url: "{{ route('MeetingMinutesAddContent') }}",
                    data: {
                        content: content,
                        topicID: $(this).data('topic-id')
                    },
                    type: "POST",
                    success: function (data) {
                        handleReturn(data, function () {
                            // do nothing;
                        });
                    }
                });
            }
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