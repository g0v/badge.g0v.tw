@extends('layouts.app')

@section('content')
<h1>建立新帳號</h1>
<p>即將為您註冊成就帳號，需要您指定一個代號，之後可以用 https://badge.g0v.tw/{您的代號} 瀏覽您的成就</p>

<form method="post">
    @csrf
    代號：<input type="text" name="id" value="{{ $prefixs[0] }}">(您的代號必須要以 {{ implode(' 或 ', $prefixs) }} 開頭)
    <br>
    連結身份：(以下資料不會公開，僅供系統連結身份使用）<br>
    <ul>
        <?php foreach ($ids as $id) { ?>
        <li>{{ $id }}</li>
        <?php } ?>
    </ul>
    連結服務及成就：(以下公開資訊將會公開)<br>
    <ul>
        <?php foreach ($ServiceUser::searchByIds($ids) as $suser) { ?>
        <li>
        [ {{ $suser->service->getData()->name }} ] {{ $suser->getData()->name }}
        <ul>
            <?php foreach ($suser->badges->take(3) as $badge) { ?>
            <li>(<?= date('Y-m-d', $badge->badge_time) ?>) {{ $badge->brief }}</li>
            <?php } ?>
            <li> ... 等 <?= count($suser->badges) ?> 筆成就</li>
        </ul>
        </li>
        <?php } ?>
    </ul>
    <button type="submit">建立</button>
</form>
@endsection
