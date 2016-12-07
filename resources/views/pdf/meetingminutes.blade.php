<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        @page {
            /*size: a4;*/
            margin: 27mm 16mm 27mm 16mm;
        }
        hr {
            padding: 0px;
            margin: 0px;
        }
        p, div {
            word-wrap: break-word;
        }
        p.indent, div.indent {
            text-indent: 2em;
        }
    </style>
</head>

<body>
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
        <p>议题{{ $idx+1 }}: {{ $topic->topicable->name }}</p>
        <?php $paragraphs = explode('<br />', $topic->meetingMinutesContent->content)?>
        @foreach($paragraphs as $paragraph)
            <div class="indent">{{ $paragraph }}</div><br>
        @endforeach
        <br>
    @endforeach

</body>

</html>

<script type="text/javascript">
//    window.print();
</script>