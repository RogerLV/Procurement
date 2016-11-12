@extends('project/display/function/frame')


@section('stageFunction')
    <form id="select-procurement-method" method="POST" enctype="multipart/form-data">

        <div>
            （一）公开招标是指实施采购的部门或其委托的采购招标代理机构，通过发布招标公告的方式邀请不特定的供应商参加投标的采购方式。<br>
            （二）邀请招标是指实施采购的部门或其委托的采购招标代理机构以投标邀请书的方式邀请三家（含）以上特定的供应商参加投标的采购方式。<br>
            （三）竞争性谈判是指实施采购的部门原则上邀请三家（含）以上的供应商就采购事宜进行谈判的采购方式。<br>
            （四）询价是指对三家（含）以上供应商的报价进行比较，以确保价格具有竞争性的采购方式。<br>
            （五）单一来源是指向唯一供应商直接谈判购买货物、工程或服务的采购方式。<br>
            <br>

            符合下列情形之一的，可采用竞争性谈判方式：<br>
            1、招标后没有供应商投标或者没有合格标的或者重新招标未能成立的。<br>
            2、技术复杂或者性质特殊的。<br>
            3、采用招标所需时间不能满足用户紧急需要的。<br><br>

            符合下列情形之一的，可采用单一来源方式：<br>
            1、只能从唯一供应商处采购的。<br>
            2、发生了不可预见的紧急情况不能从其他供应商处采购的。<br>
            3、必须保证原有采购项目一致性或者服务配套的要求，需要继续从原供应商处添购的。<br><br>

            同时符合下列条件的，可以采用询价方式：<br>
            1、单位价格在300新元（含）以下。<br>
            2、产品规格标准统一。<br>
            3、产品供应市场竞争充分。<br>
            4、价格透明度高且变化幅度小。<br><br>
        </div>

        <div class="checkbox">
            <label><input type="checkbox" name="select-from-vendor"><b>是否选型入围?</b></label>
        </div>

        <div class="form-group required">
            <label for="procurement-method-selector">选择采购方式:</label>
            <select class="form-control" required name="procurement-method-selector">
                <option selected disabled></option>
                @foreach($procurementMethods as $idx => $name)
                    <option value="{{ $idx }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>

        @if($uploadReport)
        <div class="form-group required">
            <label for="procurement-method-report">采购方式申请报告:</label>
            <input type="file" name="procurement-method-report" required>
        </div>
        @endif

        <div class="outer">
            <input class="btn btn-lg btn-primary" value="提交" type="submit">
        </div>
    </form>
@endsection


@section('script')
<script type="text/javascript">
    $(document).ready(function () {
        $("input[name=procurement-method-report]").fileinput({
            language:'zh',
            showUpload: false,
            maxFileCount: 1,
        });

        $('#select-procurement-method').submit(function (event) {
            event.preventDefault();

            var form = new FormData();
            form.append('_token', $("meta[name='csrf-token']").attr('content'));
            form.append('select-from-vendor', $("input[name=select-from-vendor]").is(':checked'));
            form.append('procurement-method', $("select[name=procurement-method-selector]").val());
            form.append('projectid', projectID);
            @if($uploadReport)
                form.append('procurement-method-report', $("input[name=procurement-method-report]")[0].files[0]);
            @endif

            var xhr = new XMLHttpRequest();
            xhr.open('POST', '{{ route(ROUTE_NAME_STAGE_SELECT_MODE) }}');
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.onload = function () {
                var data = JSON.parse(xhr.response);
                handleReturn(data);
            };

            xhr.send(form);
        });
    });
</script>
@endsection