<div class="page-header">
    <h4>留言记录:</h4>
</div>

<ul class="list-group" id="conversation-list">
    @foreach($conversation as $entry)
        <li class="list-group-item">
            <?php $user = $userInfo[$entry->lanID] ?>
            <h5 class="list-group-item-heading">
                {{ $entry->created_at }} {{ $user->getDualName() }} {{ $user->IpPhone }}
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

<script type="text/javascript">
    $(document).ready(function () {
        $('#conversation-submit').click(function () {
            var content = $.trim($('#conversation-textarea').val());
            if (0 == content.length) {
                setAlertText('留言内容不能为空。');
                $('#alert-modal').modal('show');
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')
                },
                url: "{{ route(ROUTE_NAME_CONVERSATION_ADD) }}",
                data: {
                    reference: 'Projects',
                    id: "{{ $project->id }}",
                    content: content,
                },
                type: 'POST',
                success: function (data) {
                    handleReturn(data, function () {
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

