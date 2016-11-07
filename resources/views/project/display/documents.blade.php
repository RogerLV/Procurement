<div class="page-header">
    <h4>相关文档:</h4>
</div>
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
    <tbody>
    <?php $i=1 ?>
    @foreach($documents as $document)
        <?php $user = $userInfo[$document->lanID] ?>
        <tr>
            <td>{{ $i++ }}</td>
            <td>{{ $documentTypeNames[$document->type] }}</td>
            <td>
                <a href="{{ url('document/display')."/".$document->id."/".$document->originalName }}" target="_blank">
                    {{ $document->originalName }}
                </a>
            </td>
            <td>{{ $user->getDualName() }} {{ $user->IpPhone }}</td>
            <td>{{ $document->created_at }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="form-group">
    <label for="upload-other-docs">上传其他文档:</label>
    <input type="file" class="file-input" name="upload-doc" id="upload-other-docs">
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#upload-other-docs').fileinput({
            uploadUrl: "{{ route(ROUTE_NAME_DOCUMENT_UPLOAD) }}",
            dropZoneEnabled: false,
            maxFileCount: 1,
            language:'zh',
            uploadExtraData: {
                reference: "Projects",
                id: "{{ $project->id }}",
                filetype: "{{ DOC_TYPE_OTHER_DOCS }}",
                _token: $("meta[name='csrf-token']").attr('content')
            }

        });

        $('#upload-other-docs').on('fileuploaded', function(event, data) {
            handleReturn(data.response);
        });
    });
</script>