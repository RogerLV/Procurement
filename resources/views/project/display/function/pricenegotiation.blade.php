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

<button class="btn btn-info" data-toggle="modal" data-target="#add-price-negotiation-modal">
    添加谈判记录
</button>

<div id="add-price-negotiation-modal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">添加谈判记录</h4>
            </div>
            <form id="submit-price-negotiation" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="negotiation-time">时间:</label>
                        <input type="text" class="form-control" name="negotiation-time" required>
                    </div>
                    <div class="form-group">
                        <label for="negotiation-venue">地点:</label>
                        <input type="text" class="form-control" name="negotiation-venue" required>
                    </div>
                    <div class="form-group">
                        <label for="negotiation-participants">参与人员:</label>
                        <input type="text" class="form-control" name="negotiation-participants" required>
                    </div>
                    <div class="form-group">
                        <label for="negotiation-content">谈判记录:</label>
                        <textarea rows="5" class="form-control" name="negotiation-content" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" value="添加">
                    <button class="btn btn-default" data-dismiss="modal">取消</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#submit-price-negotiation').submit(function (event) {
            event.preventDefault();
            $('#add-price-negotiation-modal').modal('hide');

            $.ajax({
                headers: headers,
                url: "{{ route(ROUTE_NAME_NEGOTIATION_ADD) }}",
                data: {
                    projectid: projectID,
                    time: $('input[name=negotiation-time]').val(),
                    venue: $('input[name=negotiation-venue]').val(),
                    participants: $('input[name=negotiation-participants]').val(),
                    content: $('textarea[name=negotiation-content]').val()
                },
                type: 'POST',
                success: function (data) {
                    handleReturn(data);
                }
            });
        });
    });
</script>