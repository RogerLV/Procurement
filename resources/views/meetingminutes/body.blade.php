<style>
    p, div {
        word-wrap: break-word;
    }
    p.indent, div.indent {
        text-indent: 2em;
    }
    p.big-font {
        font-size: 20px;
    }
    hr {
        padding: 0px;
        margin: 0px;
    }
</style>

<div style="font-size: 50px" align="center">会议纪要</div>
<div align="center">{{ $metaInfo->header }}</div>
<div align="right">采购评审委员会</div><br>
<hr><br>

<p>日期: {{ $metaInfo->date }}</p>
<p>时间: {{ $metaInfo->time }}</p>
<p>地点: {{ $metaInfo->venue }}</p>
<p>主持: {{ $metaInfo->host }}</p>
<p>出席: {{ implode($memberNames, '、 ') }} </p>
<p>列席: {{ $metaInfo->attendance }}</p>
@if(!empty($inviteeNames))
    <p>特邀列席: {{ implode($inviteeNames, '、 ') }}</p>
@endif
<p>记录: {{ $metaInfo->recorder }}</p>
<br>

@foreach($topics as $idx => $topic)
    <p>议题{{ $topics->count() > 1 ? $idx+1 : null }}: {{ $topic->topicable->name }}</p>
    <?php $paragraphs = explode('<br />', $topic->meetingMinutesContent->content)?>
    @foreach($paragraphs as $paragraph)
        <div class="indent">{{ $paragraph }}</div><br>
    @endforeach
    <br>
@endforeach

<p class="big-font">审核:
    @if(!is_null($reviewLog))
        {{ $reviewLog->operator->getDualName()." ". $reviewLog->timeAt }}
    @endif
</p>

<p class="big-font">签发:
    @if(!is_null($releaseLog))
        {{ $releaseLog->operator->getDualName()." ". $releaseLog->timeAt }}
    @endif
</p>
<br>
<hr>
<p class="big-font">行内发送: {{ $metaInfo->sendTo }}</p>
<hr>
<p class="big-font">{{ $metaInfo->footer }}</p>
<hr>