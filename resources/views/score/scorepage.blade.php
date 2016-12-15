@extends('layouts.app')


@section('HTMLContent')

    @foreach($vendors as $vendor)
        <div class="vendor-score">
            <div class="page-header">
                <h4>{{ $vendor->name }}</h4>
            </div>
            <table class="table">
                <thead>
                <th width="50px">序号</th>
                <th width="120px">打分项</th>
                <th width="300px">具体内容</th>
                <th width="50px">权重(%)</th>
                <th width="50px">分值</th>
                <th>备注</th>
                </thead>
                <tbody>
                <?php $i=1; ?>
                @foreach($entries as $entry)
                    @continue(0 == $entry->weight)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ $entry->item }}</td>
                        <td>{!! $entry->content !!}</td>
                        <td>{{ $entry->weight }}</td>
                        <td data-item-id="{{ $entry->id }}" data-vendor-id="{{ $vendor->id }}">
                            <input type="number" value="0" style="width: 40px;" class="score-input">
                        </td>
                        <td>{{ $entry->comment }}</td>
                    </tr>
                @endforeach
                <tr>
                    <th colspan="4">总分</th>
                    <th class="sum-score">0</th>
                    <th></th>
                </tr>
                </tbody>
            </table>
        </div>
        <br>
    @endforeach
    <div class="container outer">
        <button class="btn btn-primary btn-lg" data-toggle="modal"
                data-target="#confirm-submit-score-modal">
            提交
        </button>
    </div>
    <br>
    <br>
    <br>

    <div id="confirm-submit-score-modal" class="modal fade">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" type="button" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">确认提交</h4>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <th>供应商</th>
                            <th>总分</th>
                        </thead>
                        <tbody id="sum-score-list-zone"></tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" id="confirm-submit-score-button" data-dismiss="modal">提交</button>
                    <button class="btn btn-default" data-dismiss="modal">取消</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('javascriptContent')
<script type="text/javascript">
    $(document).ready(function () {
        $('input.score-input').blur(function () {
            var value = $(this).val();

            if (value > 100 || value < 0) {
                setAlertText('请输入0到100的整数。');
                $('#alert-modal').modal('show');
            }
            var weight = parseInt($(this).parent().prev().html());
            var sumCell = $(this).parents('tbody').find('th.sum-score');
            var sum = parseInt(sumCell.html());

            sum += value * weight / 100;
            sumCell.html(sum);
        });

        $('#confirm-submit-score-modal').on('show.bs.modal', function () {
            $('div.vendor-score').each(function () {
                $('#sum-score-list-zone').append($('<tr/>').append($('<td/>', {
                    text: $(this).find('div.page-header').find('h4').html()
                })).append($('<td/>', {
                    text:$(this).find('table').find('th.sum-score').html()
                })));
            });
        }).on('hide.bs.modal', function () {
            $('#sum-score-list-zone').empty();
        });

        $('#confirm-submit-score-button').click(function () {
            var scoreDetails = [];
            $('input.score-input').each(function () {
                var td = $(this).parent();
                scoreDetails.push({
                    vendorid: td.data('vendor-id'),
                    itemid: td.data('item-id'),
                    score: $(this).val()
                });
            });

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')
                },
                url: "{{ route(ROUTE_NAME_SCORE_SUBMIT_SCORE) }}",
                data: {
                    scoredetails: scoreDetails,
                    projectid: "{{ $project->id }}"
                },
                type: 'POST',
                success: function (data) {
                    handleReturn(data, function () {
                        window.location.href = "{{ url('project/display') }}"+"/"+"{{ $project->id }}";
                    });
                }
            });
        });
    });
</script>
@endsection