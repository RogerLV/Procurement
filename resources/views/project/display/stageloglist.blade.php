<div class="panel-group" id="log-accordion" role="tablist">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" href="#log-content">项目流转记录: (点击展开)</a>
            </h4>
        </div>

        <div id="log-content" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
            <div class="panel-body">
                <ul class="list-group">
                    @foreach($logList as $entry)
                        @if($entry->toStage > $entry->fromStage)
                            <li class="list-group-item list-group-item-info">
                        @elseif($entry->toStage < $entry->fromStage)
                            <li class="list-group-item list-group-item-danger">
                        @else
                            <li class="list-group-item list-group-item-default">
                        @endif
                        <h4 class="list-group-item-heading">
                            {{ $entry->timeAt }}: {{ $entry->operator->getTriName() }} {{ $stageNames[$entry->fromStage] }}
                        </h4>
                        @if(!empty($entry->comment))
                            <p class="list-group-item-text">{{ $entry->comment }}</p>
                        @endif
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

