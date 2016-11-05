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
    <div class="list-group">
        <?php $i=1 ?>
        @foreach($projects as $project)
            <a href="{{ url('project/display').'/'.$project->id }}" class="list-group-item project-entry"
               target="_blank" data-dept="{{ $project->dept }}">
                {{ $i++ }}. {{ $deptInfo[$project->dept]->deptCnName }}: {{ $project->name }}
            </a>
        @endforeach
    </div>
@endsection


@section('javascriptContent')
<script type="text/javascript">
    $(document).ready(function () {

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