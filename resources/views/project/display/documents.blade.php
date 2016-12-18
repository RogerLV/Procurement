<div class="panel-group" id="documents-accordion" role="tablist">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" href="#document-content">项目相关文档: (点击展开)</a>
            </h4>
        </div>

        <div id="document-content" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
            <div class="panel-body">

                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>文件类型</th>
                        <th>文件名</th>
                        <th>上传人</th>
                        <th>上传时间</th>
                    </tr>
                    </thead>
                    <tbody id="document-list-body">
                    <?php $i=1 ?>
                    @foreach($hyperDocs as $hyperDoc)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $hyperDoc->type }}</td>
                            <td>
                                <a href="{{ $hyperDoc->url }}" target="_blank">
                                    {{ $hyperDoc->name }}
                                </a>
                            </td>
                            <td>{{ $hyperDoc->uploader }}</td>
                            <td>{{ $hyperDoc->timeAt }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                @include('documentsingleupload', ['docTypeIns' => new \App\Logic\DocumentType\OtherDocs(), 'refresh' => false])

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        $('#upload-other-docs').on('fileuploaded', function(event, data) {
            handleReturn(data.response, function () {
                var no = parseInt($('tr:last').find('td:first').html())+1;
                var url = "{{ url('document/display') }}"
                            +"/"+data.response.info.documentIns.id
                            +'/'+data.response.info.documentIns.originalName;
                var uploaderInfo = data.response.info.userInfo.uEngName
                                    +" "+data.response.info.userInfo.uCnName
                                    +" "+data.response.info.userInfo.IpPhone;

                $('<tr/>').append($('<td/>', {
                    text: no
                })).append($('<td/>', {
                    text: data.response.info.fileType
                })).append($('<td/>').append($('<a/>', {
                    href: url,
                    target: '_blank',
                    text: data.response.info.documentIns.originalName
                }))).append($('<td/>', {
                    text: uploaderInfo
                })).append($('<td/>', {
                    text: data.response.info.documentIns.created_at
                })).appendTo($('#document-list-body'));
            });
        });
    });
</script>