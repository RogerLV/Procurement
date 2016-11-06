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
                <a href="{{ url('document/display')."/".$document->id }}" target="_blank">
                    {{ $document->originalName }}
                </a>
            </td>
            <td>{{ $user->getDualName() }} {{ $user->IpPhone }}</td>
            <td>{{ $document->created_at }}</td>
        </tr>
    @endforeach
    </tbody>
</table>