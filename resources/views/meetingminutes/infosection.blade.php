<div class="page-header">
    <h4>采购评审会议纪要</h4>
</div>

<div class="panel-group meeting-minutes-accordion" role="tablist">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">
                <a data-toggle="collapse" data-parent="div.meeting-minutes-accordion"
                    href="#meeting-minutes-{{ $reviewIns->id }}" aria-expanded="true">
                    采购评审会议纪要(点击展开)
                </a>
            </div>
        </div>
    </div>


    <div id="meeting-minutes-{{ $reviewIns->id }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
        <div class="panel-body">
            @include('meetingminutes.body')

            <h6><a href="{{ $printUrl }}" target="_blank">打印会议纪要</a></h6>
            <br>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('div.meeting-minutes-accordion').collapse();
    });
</script>







