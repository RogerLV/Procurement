<table>
    <tbody>
        <tr>
            <td width="25%"><h4>日期</h4></td>
            <td>{{ $reviewIns->date }}</td>
        </tr>
        <tr>
            <td><h4>时间</h4></td>
            <td>{{ $reviewIns->time }}</td>
        </tr>
        <tr>
            <td><h4>地点</h4></td>
            <td>{{ $reviewIns->venue }}</td>
        </tr>
        <tr>
            <td><h4>当前阶段</h4></td>
            <td>{{ $stageNames[$reviewIns->stage] }}</td>
        </tr>
        <tr>
            <td valign="top"><h4>参会委员</h4></td>
            <td>
                @foreach($members as $member)
                    {{ $deptInfo->get($member->user->dept)->deptCnName }} &nbsp;
                    {{ $member->user->getTriName() }}
                    <br>
                @endforeach
            </td>
        </tr>
        @if(0 != $specialInvitees->count())
            <tr>
                <td><h4>特邀</h4></td>
                <td>
                    @foreach($specialInvitees as $specialInvitee)
                        {{ $deptInfo->get($specialInvitee->user->dept)->deptCnName }} &nbsp;
                        {{ $specialInvitee->user->getTriName() }}
                        <br>
                    @endforeach
                </td>
            </tr>
        @endif
    </tbody>
</table>

<div class="page-header">
    <h4>议题</h4>
</div>

<div class="panel-group" id="topics-accordion">
    @foreach($topics as $idx => $topic)
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#topics-accordion" href="#topic-{{ $idx+1 }}">
                        议题{{ $idx+1 }}: {{ $topicTypeNames[$topic->type] }}
                    </a>
                </h4>
            </div>
        </div>
        <div id="topic-{{ $idx+1 }}" class="panel-collapse collapse">
            <div class="panel-body">
                <?php $documents = $topic->topicable->document ?>
                @if($topic->topicable instanceof \App\Models\Project)
                    @if($loginUserRoleID != ROLE_ID_SPECIAL_INVITE)
                        <a href="{{ url('project/display').'/'.$topic->topicable->id }}" target="_blank">
                            {{ $topic->topicable->name }}
                        </a>
                    @else
                        {{ $topic->topicable->name }}
                    @endif
                    <br><br>

                    @if('review' == $topic->type)
                        <?php $reviewReport = $documents->where('type', DOC_TYPE_REVIEW_REPORT)->sortByDesc('id')->first() ?>
                        @if(!is_null($reviewReport))
                            上会报告:<a href="{{ $reviewReport->getUrl() }}" target="_blank">{{ $reviewReport->originalName }}</a>
                            <br><br>
                        @endif

                        <?php $dueDiligenceReport = $documents->where('type', DOC_TYPE_DUE_DILIGENCE_REPORT)->sortByDesc('id')->first() ?>
                        @if(!is_null($dueDiligenceReport))
                            尽职调查报告:<a href="{{ $dueDiligenceReport->getUrl() }}" target="_blank">{{ $dueDiligenceReport->originalName }}</a>
                        @endif
                    @else
                        <?php $selectModeReport = $documents->where('type', DOC_TYPE_PROCUREMENT_APPROACH_APPLICATION)->sortByDesc('id')->first() ?>
                        @if(!is_null($selectModeReport))
                            采购方式申请报告:<a href="{{ $selectModeReport->getUrl() }}" target="_blank">{{ $selectModeReport->originalName }}</a>
                        @endif
                    @endif

                @elseif($topic->topicable instanceof \App\Models\PutRecord)
                    <p>{{ $topic->topicable->name }}</p>
                    @foreach($documents as $idx => $doc)
                        报备文档{{ $idx+1 }}: <a href="{{ $doc->getUrl() }}" target="_blank">{{ $doc->originalName }}</a>
                        <br>
                    @endforeach
                @endif
            </div>
        </div>
        <br>
    @endforeach
</div>

<script type="text/javascript">
    var reviewID = "{{ $reviewIns->id }}";
</script>