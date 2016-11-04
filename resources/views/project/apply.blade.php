@extends('layouts.app')


@section('HTMLContent')
    <form id="submit-project" action="{{ route(ROUTE_NAME_PROJECT_CREATE) }}" method="post" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <div class="form-group required">
            <label for="applicant-dept">采购部门:</label>
            <input type="text" class="form-control" name="applicant-dept" required readonly value="{{ $deptInfo->deptCnName }}">
        </div>

        <div class="form-group required">
            <label for="applicant-name">负责人姓名:</label>
            <input type="text" class="form-control" name="applicant-name" required readonly value="{{ $applicantInfo->uEngName }} {{ $applicantInfo->uCnName }}">
        </div>

        <div class="form-group required">
            <label for="applicant-contact">电话:</label>
            @if(is_null($applicantInfo->IpPhone))
                <input type="number" class="form-control" name="applicant-contact" required value="6412">
            @else
                <input type="number" class="form-control" name="applicant-contact" required readonly value="6412{{ $applicantInfo->IpPhone }}">
            @endif
        </div>

        <div class="form-group required">
            <label for="purchase-scope">采购范围:</label>
            <select class="form-control" required name="purchase-scope">
                <option selected disabled></option>
                @foreach($purchaseScopes as $idx => $name)
                    <option value="{{ $idx }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group required">
            <label for="project-name">项目主题:</label>
            <input type="text" class="form-control" required name="project-name">
        </div>

        <div class="form-group required">
            <label for="project-background">项目背景:</label>
            <textarea class="form-control" name="project-background" rows="5" required></textarea>
        </div>

        <div class="form-group required">
            <label for="project-budget">项目金额:</label>
            <input type="text" class="form-control" name="project-budget" required>
        </div>

        <div class="form-group required">
            <label for="signed-report">审批通过的立项签报:</label>
            <input type="file" class="file-input" name="signed-report" required>
        </div>

        <div class="checkbox">
            <label><input type="checkbox" name="involve-review"><b>纳入采购评审</b></label>
        </div>

        <div class="outer">
            <input class="btn btn-lg btn-primary" value="发起" type="submit">
        </div>
    </form>
    <br>
    <br>
    <br>
    <br>

@endsection


@section('javascriptContent')
<script type="text/javascript">
    $(document).ready(function () {
        $('input.file-input').fileinput({
            'language':'zh',
            'showUpload': false
        });
    });
</script>
@endsection