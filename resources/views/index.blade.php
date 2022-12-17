@extends('layouts.app')

@section('content')
<h3>最新使用者</h3>
<div class="row">
@foreach ($User::orderBy('id', 'desc')->take(100)->get() as $user)
<?php $data = $user->getData(); ?>
<div class="card col-2">
    @isset ($data->avatar)
    <div class="ratio ratio-1x1 card-img-top">
        <img style="object-fit: cover;" src="{{ $data->avatar }}">
    </div>
    @endisset
    <div class="card-body">
        <a href="/<?= urlencode($user->name) ?>">
            <h3 class="card-title">{{ $data->info->name }}</h3>
            <p>{{ '@' . $user->name }}</p>
        </a>
        <p>{{ $data->info->keyword ?? '' }}</p>
        <p>{{ $data->info->intro ?? '' }}</p>
    </div>
</div>
@endforeach
</div>
@endsection

