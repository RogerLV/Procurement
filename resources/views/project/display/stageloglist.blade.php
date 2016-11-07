<div class="page-header">
    <h4>流转记录:</h4>
</div>

<ul class="list-group">
    @foreach($logList as $entry)
        <li class="list-group-item">
            <?php $user = $userInfo[$entry->lanID] ?>
            {{ $entry->timeAt }}: {{ $user->getDualName() }} {{ $user->IpPhone }} {{ $stageNames[$entry->toStage] }}
        </li>
    @endforeach
</ul>