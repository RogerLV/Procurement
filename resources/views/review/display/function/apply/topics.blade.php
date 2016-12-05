<style type="text/css">
    button.remove-topic {
        float: right;
    }
</style>


<div class="page-header">
    <h4>添加议题</h4>
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
                    <p>{{ $topic->topicable->name }}</p>
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
    });
</script>