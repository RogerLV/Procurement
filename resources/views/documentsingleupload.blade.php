<?php $refresh = isset($refresh) ? $refresh : true; ?>
<?php $uploadedTypes = isset($uploadedTypes) ? $uploadedTypes : []; ?>

<div class="form-group">
    <label for="upload-{{ $docTypeIns->getEngName() }}">上传{{ $docTypeIns->getCnName() }}:</label>
    @if(in_array($docTypeIns->getTypeID(), $uploadedTypes))
        <span class="glyphicon glyphicon-ok" style="color:green"></span>
    @endif
    <input type="file" name="upload-doc" id="upload-{{ $docTypeIns->getEngName() }}">
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#upload-{{ $docTypeIns->getEngName() }}').fileinput({
            uploadUrl: "{{ route(ROUTE_NAME_DOCUMENT_UPLOAD) }}",
            dropZoneEnabled: false,
            maxFileCount: 1,
            language:'zh',
            uploadExtraData: {
                reference: "Projects",
                id: projectID,
                filetype: "{{ $docTypeIns->getTypeID() }}",
                _token: $("meta[name='csrf-token']").attr('content')
            }
        });

        @if($refresh)
            $('#upload-{{ $docTypeIns->getEngName() }}').on('fileuploaded', function (event, data) {
                handleReturn(data.response);
            });
        @endif
    });
</script>