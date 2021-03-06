<button class="btn btn-primary btn-block" data-toggle="modal" data-target="#add-topic-modal">
    添加议题
</button>

<div id="add-topic-modal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">添加议题</h4>
            </div>
            <div class="modal-body">
                <ul class="nav nav-pills">
                    <li class="active"><a data-toggle="tab" href="#topic-type-procurement-review">采购项目评审</a></li>
                    <li><a data-toggle="tab" href="#topic-type-select-mode">采购方式预审</a></li>
                    <li><a data-toggle="tab" href="#topic-type-put-records">报备</a></li>
                </ul>

                <div class="tab-content">
                    <div id="topic-type-procurement-review" class="tab-pane fade in active">
                        <br>
                        <div class="form-group">
                            <select class="form-control" id="procurement-selector">
                                @foreach($reviewOptions as $option)
                                    <option value="{{ $option->id }}">{{ $option->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div id="topic-type-select-mode" class="tab-pane fade">
                        <br>
                        <div class="form-group">
                            <select class="form-control">
                                @foreach($selectModeOptions as $option)
                                    <option value="{{ $option->id }}">{{ $option->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div id="topic-type-put-records" class="tab-pane fade">
                        <br>
                        <div class="form-group">
                            <label>报备内容</label>
                            <input type="text" class="form-control" id="record-name-input">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" id="add-topic-button" data-dismiss="modal">添加</button>
                <button class="btn btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        $('#add-topic-button').click(function () {
            var tabDiv = $('div.tab-content').find('div.active');
            var data = {
                reviewMeetingID: "{{ $reviewMeetingIns->id }}",
            };
            var url = "{{ route(ROUTE_NAME_TOPIC_ADD_PROJECT) }}";
            switch (tabDiv.attr('id')) {
                case 'topic-type-procurement-review':
                    data.projectID = tabDiv.find('select').val();
                    data.type = 'review';
                    break;

                case 'topic-type-select-mode':
                    data.projectID = tabDiv.find('select').val();
                    data.type = 'discussion';
                    break;

                case 'topic-type-put-records':
                    var name = $.trim($('#record-name-input').val());
                    if (0 == name.length) {
                        setAlertText('报备内容不能为空');
                        $('#alert-modal').modal('show');
                        return;
                    }
                    data.name = name;
                    data.type = 'putRecord';
                    url = "{{ route(ROUTE_NAME_TOPIC_ADD_PUT_RECORD) }}";
                    break;
            }

            $.ajax({
                headers: headers,
                url: url,
                data: data,
                type: 'POST',
                success: function (data) {
                    handleReturn(data);
                }
            });
        });
    });
</script>