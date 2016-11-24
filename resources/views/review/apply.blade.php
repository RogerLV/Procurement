@extends('layouts.app')


@section('CSSContent')
<style type="text/css">
    button.remove-topic {
        float: right;
    }
</style>
@endsection


@section('HTMLContent')
    <div class="form-group required">
        <label>日期</label>
        <input type="text" class="form-control review-meeting-info" data-attr="date" value="{{ $reviewMeetingIns->date }}">
    </div>

    <div class="form-group required">
        <label>时间</label>
        <input type="text" class="form-control review-meeting-info" data-attr="time" value="{{ $reviewMeetingIns->time }}">
    </div>

    <div class="form-group required">
        <label>地点</label>
        <input type="text" class="form-control review-meeting-info" data-attr="venue" value="{{ $reviewMeetingIns->venue }}">
    </div>

    <div class="panel-group" id="topics-accordion">
        @foreach($topics as $idx => $topic)
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#topics-accordion" href="#topic-{{ $idx+1 }}">
                            议题{{ $idx+1 }}: {{ $topicTypeNames[$topic->type] }}
                        </a>
                        <button class="btn btn-danger btn-xs remove-topic" data-topic-id="{{ $topic->id }}"
                                data-toggle="modal" data-target="#confirm-remove-topic-modal">
                            <span class="glyphicon glyphicon-remove"></span>
                        </button>
                    </h4>
                </div>
            </div>
            <div id="topic-{{ $idx+1 }}" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php $documents = $topic->topicable->document ?>
                    @if($topic->topicable instanceof \App\Models\Project)
                        <a href="{{ url('project/display').'/'.$topic->topicable->id }}" target="_blank">
                            {{ $topic->topicable->name }}
                        </a>
                        <br><br>

                        @if('review' == $topic->type)
                            <?php $reviewReport = $documents->where('type', DOC_TYPE_REVIEW_REPORT)->sortByDesc('id')->first() ?>
                            @if(!is_null($reviewReport))
                                上会报告:<a href="{{ $reviewReport->getUrl() }}" target="_blank">{{ $reviewReport->originalName }}</a>
                                <br><br>
                            @endif

                            <?php $dueDiligenceReport = $documents->where('type', DOC_TYPE_DUE_DILIGENCE_REPORT)->sortByDesc('id')->first() ?>
                            @if(!is_null($dueDiligenceReport))
                                尽职调查报告:<a href="{{ $dueDiligenceReport->getUrl() }}" target="_blank">{{ $dueDiligenceReport->originalName }}</a>
                            @endif
                        @else
                            <?php $selectModeReport = $documents->where('type', DOC_TYPE_PROCUREMENT_APPROACH_APPLICATION)->sortByDesc('id')->first() ?>
                            @if(!is_null($selectModeReport))
                                采购方式申请报告:<a href="{{ $selectModeReport->getUrl() }}" target="_blank">{{ $selectModeReport->originalName }}</a>
                            @endif
                        @endif

                    @elseif($topic->topicable instanceof \App\Models\PutRecord)
                        <p>{{ $topic->topicable->content }}</p>
                        @foreach($documents as $idx => $doc)
                            报备文档{{ $idx+1 }}: <a href="{{ $doc->getUrl() }}" target="_blank">{{ $doc->originalName }}</a>
                            <br>
                        @endforeach
                        <br>
                        <div class="form-group">
                            <label>上传报备文档:</label>
                            <input type="file" name="upload-doc" id="upload-topic-{{ $topic->id }}">
                        </div>
                        <script type="text/javascript">
                            $(document).ready(function () {
                                $('#upload-topic-{{ $topic->id }}').fileinput({
                                    uploadUrl: "{{ route(ROUTE_NAME_DOCUMENT_UPLOAD) }}",
                                    dropZoneEnabled: false,
                                    maxFileCount: 1,
                                    language:'zh',
                                    uploadExtraData: {
                                        reference: "PutRecords",
                                        id: "{{ $topic->topicable->id }}",
                                        filetype: "{{ DOC_TYPE_PUT_RECORDS }}",
                                        _token: $("meta[name='csrf-token']").attr('content')
                                    }
                                }).on('fileuploaded', function (event, data) {
                                    handleReturn(data.response);
                                });
                            });
                        </script>
                        {{--TODO: display all related files and file upload button--}}
                    @endif
                </div>
            </div>
            <br>
        @endforeach
    </div>

    <div id="confirm-remove-topic-modal" class="modal fade">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" type="button" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">确定删除</h4>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" id="confirm-remove-topic-button" data-dismiss="modal">确定</button>
                    <button class="btn btn-default" data-dismiss="modal">取消</button>
                </div>
            </div>
        </div>
    </div>

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
                                <input type="text" class="form-control" id="record-content-input">
                            </div>
                            {{--<div class="form-group">--}}
                                {{--<label>上传文档:</label>--}}
                                {{--<input type="file" class="file-input" id="put-record-file">--}}
                            {{--</div>--}}
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
@endsection


@section('javascriptContent')
<script type="text/javascript">
    $(document).ready(function () {

        var topicID;
        $('#confirm-remove-topic-modal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            topicID = button.data('topic-id');
            var topicName = button.parent().find('a').html();
            $(this).find('div.modal-body').html(topicName);
        }).on('hide.bs.modal', function () {
            $(this).find('div.modal-body').html('');
        });

        $('#confirm-remove-topic-button').click(function () {
            $.ajax({
                headers: headers,
                url: "{{ route(ROUTE_NAME_TOPIC_REMOVE) }}",
                data: {
                    topicID: topicID,
                    reviewMeetingID: "{{ $reviewMeetingIns->id }}"
                },
                type: 'POST',
                success: function (data) {
                    handleReturn(data);
                }
            });
        });

        $('input.file-input').fileinput({
            language:'zh',
            showUpload: false
        });

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
                    var content = $.trim($('#record-content-input').val());
                    if (0 == content.length) {
                        setAlertText('报备内容不能为空');
                        $('#alert-modal').modal('show');
                        return;
                    }
                    data.content = content;
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

        $('input.review-meeting-info').blur(function () {
            var attr = $(this).data('attr');
            var val = $.trim($(this).val());

            if (val.length != 0) {
                $.ajax({
                    headers: headers,
                    url: "{{ route(ROUTE_NAME_REVIEW_EDIT) }}",
                    data: {
                        reviewMeetingID: "{{ $reviewMeetingIns->id }}",
                        attr: attr,
                        value: val
                    },
                    type: "POST",
                    success: function (data) {
                        handleReturn(data, function () {
                            // do nothing
                        });
                    }
                });
            }
        });

    });
</script>
@endsection