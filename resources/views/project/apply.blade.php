@extends('layouts.app')


@section('HTMLContent')
    <form id="submit-project" method="post" enctype="multipart/form-data">
        <div class="form-group required">
            <label for="applicant-department">采购部门:</label>
            <input type="text" class="form-control" name="applicant-department" required readonly value="{{ $deptInfo->deptCnName }}">
        </div>

        <div class="form-group required">
            <label for="applicant-name">负责人姓名:</label>
            <input type="text" class="form-control" name="applicant-name" required readonly value="{{ $applicantInfo->uEngName }} {{ $applicantInfo->uCnName }}">
        </div>

        <input type="hidden" name="applicant-dept" value="{{ $deptInfo->dept }}">
        <input type="hidden" name="applicant-lanid" value="{{ $applicantInfo->lanID }}">

        <div class="form-group required">
            <label for="applicant-contact">电话:</label>
            <input type="number" class="form-control" name="applicant-contact" required readonly value="6412{{ $applicantInfo->IpPhone }}">
        </div>

        <div class="form-group required">
            <label for="procurement-scope">采购范围:</label>
            <select class="form-control" required name="procurement-scope">
                <option selected disabled></option>
                @foreach($procurementScopes as $idx => $name)
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

        <p><i>以下事项应纳入我行采购评审范围:</i></p>
        <ol>
            <i><li>单笔合同金额高于10万新元(含)的采购项目。定期支付款项的租约或服务合约,若签订具体合同的,按合同金额判定。若未涉及具体合同金额的,则按年租金或年费判定。</li></i>
            <i><li>按照有关规定组织实施的供应商选型入围和协议供货项目。</li></i>
            <i><li>管理层批示需要提交采购评审的项目。</li></i>
        </ol>

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
            language:'zh',
            showUpload: false,
            maxFileCount: 1,
        });

        $('#submit-project').submit(function (event) {
            event.preventDefault();

            var form = new FormData();
            form.append('_token', $("meta[name='csrf-token']").attr('content'));
            form.append('applicant-dept', $("input[name=applicant-dept]").val());
            form.append('applicant-lanid', $("input[name=applicant-lanid]").val());
            form.append('procurement-scope', $("select[name=procurement-scope]").val());
            form.append('project-name', $("input[name=project-name]").val());
            form.append('project-background', $("textarea[name=project-background]").val());
            form.append('project-budget', $("input[name=project-budget]").val());
            form.append('signed-report', $("input[name=signed-report]")[0].files[0]);
            form.append('involve-review', $("input[name=involve-review]").is(':checked'));

            var xhr = new XMLHttpRequest();
            xhr.open('POST', '{{ route(ROUTE_NAME_PROJECT_CREATE) }}', true);

            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.onload = function () {
                var data = JSON.parse(xhr.response);
                handleReturn(data, function () {
                    window.location.href = "{{ url('project/display') }}"+"/"+data.info.id;
                });
            };

            setAlertText('正在创建项目');
            $('#alert-modal').modal('show');
            xhr.send(form);
        });
    });
</script>
@endsection