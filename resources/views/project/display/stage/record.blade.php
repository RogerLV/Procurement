@if($toBeScore)
    <div class="page-header">
        <h4>供应商评分</h4>
    </div>

    <table class="table">
        @foreach($vendors as $vendor)
            <tr>
                <td>{{ $vendor->name }}</td>
                <td>{{ $vendorFinalScores[$vendor->id] }}</td>
            </tr>
        @endforeach
    </table>

    <button class="btn btn-info btn-block"
            onclick="window.open('{{ url('score/overview') }}'+'/'+projectID, '_blank')">
        查看评分明细
    </button>
    <br>
@endif

@if($priceNegotiation)
    <div class="panel-group" id="negotiation-accordion" role="tablist">
        <div class="panel panel-default">
            <button class="btn btn-info btn-block" data-parent="#negotiation-accordion"
                    data-toggle="collapse" href="#collapseOne" role="tab" id="headingOne">
                    查看谈判记录
            </button>

            <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                <div class="panel-body">
                    @foreach($negotiations as $negotiation)
                        <h4>第{{ $negotiation->roundNo }}轮谈判记录</h4>
                        <table class="table table-bordered">
                            <tr>
                                <th width="20%">时间</th>
                                <td>{{ $negotiation->time }}</td>
                            </tr>
                            <tr>
                                <th>地点</th>
                                <td>{{ $negotiation->venue }}</td>
                            </tr>
                            <tr>
                                <th>参与人员</th>
                                <td>{{ $negotiation->participants }}</td>
                            </tr>
                            <tr>
                                <th>谈判记录</th>
                                <td>{!! $negotiation->content !!}</td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#negotiation-accordion').collapse();
        });
    </script>
@endif