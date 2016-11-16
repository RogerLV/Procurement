@extends('project/display/function/frame')


@section('stageFunction')
    <table>
        <tr>
            <td><h4>采购方式:</h4></td>
            <td>{{ $project->getProcurement() }}</td>
        </tr>
        @if($project->involveReview)
            <tr>
                <td><h4>采购方式申请报告:</h4></td>
                <td><iframe src="{{ $procurementMethodReport->getUrl() }}" allowfullscreen width="700px" height="500px"></iframe></td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <p><a href="{{ $procurementMethodReport->getUrl() }}" target="iframe_a">打开浏览</a></p>
                </td>
            </tr>
        @endif
    </table>

    @include('project/display/function/approveorreject')

@endsection