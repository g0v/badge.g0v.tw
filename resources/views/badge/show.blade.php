@extends('layouts.app')
<?php
$title = sprintf("[ %s ] %s", $service->getData()->name, $badge->brief);
$records = $ServiceBadge::getBadgeList($service->id, $badge->badge_hash);
foreach (array_slice($records, 1, 5) as $idx => $record) {
    $terms[] = sprintf("%d %s", $idx + 1, $record->name);
}
$description = implode("\n", $terms);
?>
@section('title', $title)
@section('description', $description)

@section('content')
<h3>[ {{ $service->getData()->name }} ] {{ $badge->brief }}</h3>
<table class="table">
    <thead>
        <tr>
            <th>排名</th>
            <th>時間</th>
            <th>使用者</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($records as $record) { ?>
    <tr>
        <td id="rank-<?= $record->rank ?>"><?= $record->rank ?></td>
        <td><?= date('Y-m-d', $record->badge_time) ?></td>
        <td>
            <?php if (!$service->getData()->public) { ?>
            <?php if ($record->user_id and $u = User::find_by_name($record->user_id) and $u->isServiceUserPublic($record->service_user_id)) { ?>
                <a href="/<?= urlencode($record->user_id) ?>">{{ $record->name }}</a>
                <?php } else { ?>
                未公開
                <?php } ?>
            <?php } else { ?>
                <?php if ($record->user_id) { ?>
                <a href="/<?= urlencode($record->user_id) ?>">{{ $record->name }}</a>
                <?php } else { ?>
                {{ $record->name }}
                <?php } ?>
            <?php } ?>
        </td>
    </tr>
    <?php } ?>
    </tbody>
</table>
@endsection
