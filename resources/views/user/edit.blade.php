@extends('layouts.app')

<?php
$data = $user->getData();
$info = $data->info;

?>
@section('content')
<h1>編輯我的個人資料</h1>
<h2>頭像編輯</h2>
<?php if ($data->avatar) { ?>
<img width="128" height="128" src="{{ $data->avatar }}">
<?php } ?>
<ul>
    <li><a href="/_/user/slacklogin?next=/_/user/setavatar">使用 Slack 的頭像</a></li>
    <li><a href="/_/user/googlelogin?next=/_/user/setavatar">使用 Google 的頭像</a></li>
    <li><a href="/_/user/githublogin?next=/_/user/setavatar">使用 Github 的頭像</a></li>
</ul>
<h2>身份編輯</h2>
<ul>
    <?php foreach (json_decode($user->ids) as $id) { ?>
    <li>{{ $id }}</li>
    <?php } ?>
</ul>
<ul>
    <li><a href="/_/user/slacklogin?next=/_/user/addid">新增 Slack 的身份</a></li>
    <li><a href="/_/user/googlelogin?next=/_/user/addid">新增 Google 的身份</a></li>
    <li><a href="/_/user/githublogin?next=/_/user/addid">新增 Github 的身份</a></li>
</ul>
<h2>連結服務帳號編輯</h2>
<form method="post" action="/_/user/setpublic">
    @csrf
<span>可以勾選想要顯示的服務</span>
<ul>
    <?php foreach ($ServiceUser::searchByIds(json_decode($user->ids)) as $suser) { ?>
    <li>
    <input type="checkbox" name="service_user[<?= $suser->id ?>]" value="1" <?= $user->isServiceUserPublic($suser) ? ' checked': '' ?>>
    [ {{ $suser->service->getData()->name }} ] {{ $suser->getData()->name }}
    </li>
    <?php } ?>
</ul>
<button class="btn btn-primary" type="submit">修改顯示設定</button>
</form>
<h2>個人資料編輯</h2>
<form method="post" action="/_/user/edit?method=info">
    @csrf
    代號：{{ $user->name }}<br>
    顯示名稱：<input type="text" name="info[name]" value="{{ $info->name }}"><br>
    三個關鍵字：<input type="text" name="info[keyword]" value="{{ $info->keyword }}"><br>
    自我介紹：<textarea name="info[intro]">{{ $info->intro }}</textarea>
    <button type="submit">修改</button>
</form>
<h2>刪除我的帳號</h2>
<form method="post" action="/_/user/delete">
    @csrf
    輸入帳號確認：<input type="text" name="name" placeholder="{{ $user->name }}" pattern="{{ preg_quote($user->name) }}">
    <button type="submit">刪除</button>
</form>
@endsection
