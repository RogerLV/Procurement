<div class="panel-group" id="conversation-accordion" role="tablist">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" href="#conversation-content">留言记录: (点击展开)</a>
            </h4>
        </div>

        <div id="conversation-content" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
            <div class="panel-body">
                <ul class="list-group" id="conversation-list">
                @foreach($conversation as $entry)
                    <li class="list-group-item">
                        <h5 class="list-group-item-heading">
                            {{ $entry->created_at }} {{ $entry->composer->getTriName() }}
                        </h5>
                        <p class="list-group-item-text">{{ $entry->content }}</p>
                    </li>
                @endforeach
                </ul>

                <div class="input-group">
                    <textarea class="form-control" rows="4" id="conversation-textarea" placeholder="请输入留言"></textarea>
                    <span class="input-group-btn" style="vertical-align:top;">
                        <button class="btn btn-primary" id="conversation-submit" style="height: 94px; width: 60px">
                            提交
                        </button>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#conversation-submit').click(function () {
            var content = $.trim($('#conversation-textarea').val());
            if (0 == content.length) {
                setAlertText('留言内容不能为空。');
                $('#alert-modal').modal('show');
            }

            $.ajax({
                headers: headers,
                url: "{{ route(ROUTE_NAME_CONVERSATION_ADD) }}",
                data: {
                    reference: 'Projects',
                    id: "{{ $project->id }}",
                    content: content,
                },
                type: 'POST',
                success: function (data) {
                    handleReturn(data, function () {
                        $('#conversation-textarea').val('');

                        var header = data.info.conversationIns.created_at
                                     +" "+data.info.userInfo.uEngName
                                     +" "+data.info.userInfo.uCnName
                                     +" "+data.info.userInfo.IpPhone;

                        $('<li/>', {
                            class: 'list-group-item'
                        }).prepend($('<h5/>', {
                            class: 'list-group-item-heading',
                            text: header
                        })).append($('<p/>', {
                            class: 'list-group-item-text',
                            text: data.info.conversationIns.content
                        })).prependTo('#conversation-list');
                    });
                }
            });
        });
    });
</script>

