@extends('layouts.app')


@section('CSSContent')
<style type="text/css">
    textarea {
        border: none;
        width: 100%;
        resize: none;
    }
</style>
<link rel="stylesheet" href="{{ asset('css/jquery-ui.min.css') }}">
@endsection


@section('HTMLContent')
    <label>添加供应商:</label>
    <div class="input-group">
        <input class="form-control" id="add-vendor-input">
        <span class="input-group-btn">
            <button class="btn btn-primary" id="add-vendor-button">
                添加
            </button>
        </span>
    </div>
    <table class="table">
        <tbody id="vendor-list-body">
            @foreach($vendors as $vendor)
                <tr data-vendor-id="{{ $vendor->id }}">
                    <td>{{ $vendor->name }}</td>
                    <td>
                        <button class="btn btn-danger btn-xs remove-vendor">
                            <span class="glyphicon glyphicon-remove"></span>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <br>
    <br>

    <p style="color:red"><i>注意事项:</i></p>
    <ul>
        <li style="color: red"><i>单击要修改内容进行编辑,全部编辑完成后提交。</i></li>
        <li style="color: red"><i>若要删除评分项,将权重置0即可。</i></li>
        <li style="color: red"><i>提交后模板和供应商信息将无法修改。</i></li>
    </ul>

    <div class="form-group">
        <label for="select-template">选择模板:</label>
        <select class="form-control" id="select-template">
            @foreach($options as $option)
                <option value="{{ $option->nameID }}">{{ $option->name }}</option>
            @endforeach
        </select>
    </div>


    <table class="table">
        <thead>
            <th width="50px">序号</th>
            <th width="200px">评分项</th>
            <th width="">具体内容</th>
            <th>权重(%)</th>
            <th>参考权重(%)</th>
            <th>备注</th>
        </thead>
        <tbody id="template-zone">
            <?php $i=1 ?>
            @foreach($scoreTemplates as $entry)
                <tr class="template-entry">
                    <td>{{ $i++ }}</td>
                    <td><textarea rows="3" class="entry-item">{{ $entry->item }}</textarea></td>
                    <td><textarea rows="3" class="entry-content">{{ $entry->content }}</textarea></td>
                    <td><input type="number" value="0" class="entry-weight"></td>
                    <td>{{ $entry->bottom }}-{{ $entry->top }}</td>
                    <td><textarea rows="3" class="entry-comment">{{ $entry->comment }}</textarea></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="container outer">
        <button class="btn btn-primary btn-lg" data-toggle="modal"
                data-target="#confirm-commit-score-template-modal">
            完成编辑
        </button>
    </div>
    <br>
    <br>


    <div id="confirm-commit-score-template-modal" class="modal fade">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" type="button" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">确定提交打分模板?</h4>
                </div>
                <div class="modal-body">
                    提交后模板内容和供应商信息将无法修改。
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" id="confirm-commit-score-template-button" data-dismiss="modal">确认提交</button>
                    <button class="btn btn-default" data-dismiss="modal">取消</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('javascriptContent')
<script type="text/javascript" src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function () {
        var headers = {
            'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')
        };
        $('#add-vendor-input').autocomplete({
            source: JSON.parse('{!! $vendorOptions !!}')
        });

        $('#select-template').change(function () {
            var nameid = $(this).val();
            $.ajax({
                headers: headers,
                url: "{{ route(ROUTE_NAME_SCORE_SELECT_TEMPLATE) }}",
                data: {
                    nameid: nameid,
                    scope: "{{ $project->scope }}"
                },
                type: 'POST',
                success: function (data) {
                    handleReturn(data, function () {
                        $('#template-zone').empty();
                        $.each(data.info, function (idx, entry) {
                            $('#template-zone').append($('<tr/>', {
                                class: 'template-entry'
                            }).append($('<td/>', {
                                text: idx+1
                            })).append($('<td/>').append($('<textarea/>', {
                                rows: 3,
                                text: entry.item,
                                class: 'entry-item'
                            }))).append($('<td/>').append($('<textarea/>', {
                                rows: 3,
                                text: entry.content.replace(/&#13;/gi,"\n"),
                                class: 'entry-content'
                            }))).append($('<td/>').append($('<input/>', {
                                type: 'number',
                                value: 0,
                                class: 'entry-weight'
                            }))).append($('<td/>', {
                                text: entry.bottom+'-'+entry.top
                            })).append($('<td/>').append($('<textarea/>', {
                                rows: 3,
                                text: entry.comment,
                                class: 'entry-comment'
                            }))));
                        });
                    })
                }
            });
        });

        var removeVendor = function () {
            var tr = $(this).parents('tr');
            var vendorID = tr.data('vendor-id');
            $.ajax({
                headers: headers,
                url: "{{ route(ROUTE_NAME_VENDOR_REMOVE) }}",
                data: {
                    vendorid: vendorID,
                    projectid: "{{ $project->id }}",
                },
                type: 'post',
                success: function (data) {
                    handleReturn(data, function () {
                        tr.hide();
                    });
                }
            });
        }

        $('button.remove-vendor').bind('click', removeVendor);

        $('#add-vendor-button').click(function () {
            var vendorName = $('#add-vendor-input').val();
            $.ajax({
                headers: headers,
                url: "{{ route(ROUTE_NAME_VENDOR_ADD) }}",
                data: {
                    projectid: "{{ $project->id }}",
                    vendorName: vendorName
                },
                type: 'POST',
                success: function (data) {
                    handleReturn(data, function () {
                        $('#vendor-list-body').append($('<tr/>').data(
                                'vendor-id',
                                data.info.id
                        ).append($('<td/>', {
                            text: data.info.name
                        })).append($('<td/>').append($('<button/>', {
                            class: 'btn btn-danger btn-xs'
                        }).append($('<span/>', {
                            class: 'glyphicon glyphicon-remove'
                        })).bind('click', removeVendor))));
                    })
                }
            });
        });

        $('#confirm-commit-score-template-button').click(function () {
            var entries = [];
            var weightSum = 0;

            $('#template-zone').find('tr.template-entry').each(function() {
                weightSum += parseInt($(this).find('input.entry-weight').val());
                entries.push({
                    item: $(this).find('textarea.entry-item').val(),
                    content: $(this).find('textarea.entry-content').val(),
                    weight: $(this).find('input.entry-weight').val(),
                    comment: $(this).find('textarea.entry-comment').val()
                });
            });

            if (100 != weightSum) {
                setAlertText('当前权重之和为'+weightSum+', 必须为100');
                $('#alert-modal').modal('show');
                return;
            }

            $.ajax({
                headers: headers,
                url: "{{ route(ROUTE_NAME_SCORE_COMMIT_ITEMS) }}",
                data: {
                    items: entries,
                    projectid: "{{ $project->id }}"
                },
                type: 'POST',
                success: function (data) {
                    handleReturn(data, function () {
                        window.location.href = "{{ url('project/display') }}"+"/"+"{{ $project->id }}";
                    })
                }
            });
        });
    });
</script>
@endsection