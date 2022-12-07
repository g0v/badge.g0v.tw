@extends('layouts.app')

@php
$data = $user->getData();
if ($data->info->name == $user->name) {
	$title = $data->info->name;
} else {
	$title = "{$user->name}({$data->info->name})";
}
$description = trim($data->info->keyword . "\n" . $data->info->intro);
@endphp
@section('title', $title)
@section('description', $description)

@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-3 col-md-3">
            <div class="card">
                <?php if ($data->avatar) { ?>
                <img class="card-img-top" src="{{ $data->avatar }}" width="260" height="260">
                <?php } ?>
                <div class="card-body">
                    <h3 class="card-title">{{ $data->info->name }}</h3>
                    <p>{{ '@' . $user->name }}</p>
                    <p>{{ $data->info->keyword }}</p>
                    <p>{{ $data->info->intro }}</p>
                </div>
            </div>
        </div>
        <div class="col-sm-9 col-md-9">
            <?php foreach ($ServiceUser::searchByIds(json_decode($user->ids)) as $suser) { ?>
            <?php if (!$user->isServiceUserPublic($suser)) { continue; } ?>
            <div class="card" id="serviceuser-<?= $suser->id ?>">
                <div class="card-header">
                    <h5 class="card-title">
                    <?php if ($link = $suser->getLink()) { ?>
                        <a href="{{ $link }}" target="_blank">[ {{$suser->service->getData()->name }} ] {{ $suser->getData()->name }}</a>
                    <?php } else { ?>
                    [ {{ $suser->service->getData()->name }} ] {{ $suser->getData()->name }}
                    <?php } ?>

                    <a href="#serviceuser-<?= $suser->id ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-link-45deg" viewBox="0 0 16 16">
                              <path d="M4.715 6.542 3.343 7.914a3 3 0 1 0 4.243 4.243l1.828-1.829A3 3 0 0 0 8.586 5.5L8 6.086a1.002 1.002 0 0 0-.154.199 2 2 0 0 1 .861 3.337L6.88 11.45a2 2 0 1 1-2.83-2.83l.793-.792a4.018 4.018 0 0 1-.128-1.287z"/>
                              <path d="M6.586 4.672A3 3 0 0 0 7.414 9.5l.775-.776a2 2 0 0 1-.896-3.346L9.12 3.55a2 2 0 1 1 2.83 2.83l-.793.792c.112.42.155.855.128 1.287l1.372-1.372a3 3 0 1 0-4.243-4.243L6.586 4.672z"/>
                          </svg>
                      </a>
                  </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php $ranks = $ServiceBadge::getBadgeRank($suser->badges) ?>
                        <?php foreach ($suser->badges as $badge) { ?>
                        <div class="col-sm-4">
                            <div class="card" style="width: 18rem;">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $badge->brief }}</h5>
                                    <h6 class="card-subtitle"><?= date('Y-m-d', $badge->badge_time) ?></h6>
                                    <?php $data = $badge->getData() ?>
                                    <?php if (property_exists($data, 'title')) { ?>
<?php $data->title = mb_strimwidth($data->title, 0, 80, '...', 'UTF-8') ?>
                                        <?php if (property_exists($data, 'url')) { ?>
                                        <p>
                                        <a href="{{ $data->url }}" target="_blank">{{ $data->title }}</a></p>
                                        <?php } else { ?>
                                        <p>{{ $data->title }}</p>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                                <div class="card-footer text-end">
                                    <a href="/_/badge/show?service_id=<?= $badge->service_id ?>&hash=<?= $badge->badge_hash ?>#rank-<?= $ranks->{$badge->id}[0] ?>"><?= $ranks->{$badge->id}[0] ?> / <?= $ranks->{$badge->id}[1] ?></a>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
@endsection
