<div class="page-header">
    <h4>流转记录:</h4>
</div>

<ul class="list-group">
    @foreach($logList as $entry)
        <li class="list-group-item">
            {{ $entry->timeAt }}: {{ $entry->operator->getTriName() }} {{ $stageNames[$entry->fromStage] }}
        </li>
    @endforeach
</ul>