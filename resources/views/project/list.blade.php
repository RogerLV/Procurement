@extends('layouts.app')


@section('HTMLContent')
    <!-- department selector -->
    <div class="btn-group">
        <button type="button" class="btn">选择部门</button>
        <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="caret"></span>
            <span class="sr-only">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu">
            @foreach($projects->pluck('dept')->unique()->all() as $dept)
                <li class="filter-dept" data-dept="{{ $dept }}"><a>{{ $deptInfo[$dept]->deptCnName }}</a></li>
            @endforeach
            <li class="show-all"><a>显示所有</a></li>
        </ul>
    </div>
    <br>
    <br>

    {{--project list--}}
    <?php $i=1 ?>
    @foreach($projects as $project)
        <div>
            <a href="{{ url('project/display').'/'.$project->id }}" class="list-group-item project-entry"
               target="_blank" data-dept="{{ $project->dept }}">
                {{ $i++ }}. {{ $deptInfo[$project->dept]->deptCnName }}: {{ $project->name }}
            </a>
            @if($removable)
                <button class="btn btn-danger btn-xs" data-project-id="{{ $project->id }}"
                        data-toggle="modal" data-target="#confirm-remove-project-modal">
                    <span class="glyphicon glyphicon-remove"></span>
                </button>
            @endif
        </div>
    @endforeach

    <div id="confirm-remove-project-modal" class="modal fade">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" type="button" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">确认删除采购项目?</h4>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" data-dismiss="modal" id="confirm-remove-project-button">确认</button>
                    <button class="btn btn-default" data-dismiss="modal">取消</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('javascriptContent')
<script type="text/javascript">
    $(document).ready(function () {

        var projectID;

        $('#confirm-remove-project-modal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var projectName = button.parent().find('a').html();
            projectID = button.data('project-id');
            $(this).find('div.modal-body').html(projectName);
        }).on('hide.bs.modal', function () {
            $(this).find('div.modal-body').html('');
        });

        $('#confirm-remove-project-button').click(function () {
            $.ajax({
                headers: headers,
                url: "{{ route('ProjectRemove') }}",
                data: {projectID: projectID},
                type: 'POST',
                success: function (data) {
                    handleReturn(data);
                }
            });
        });

        $('li.filter-dept').click(function () {
            var dept = $(this).data('dept');
            $('a.project-entry').each(function () {
                if ($(this).data('dept') != dept ) {
                    $(this).hide();
                } else {
                    $(this).show();
                }
            });
        });

        $('li.show-all').click(function () {
            $('a.project-entry').show();
        });
    });
</script>
@endsection