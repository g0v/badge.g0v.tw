<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
<title>g0v 成就系統 - @yield('title')</title>
<meta property="og:description" content="@yield('description', '')"/>
<script src="//ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.1/js/bootstrap.bundle.min.js" integrity="sha512-1TK4hjCY5+E9H3r5+05bEGbKGyK506WaDPfPe1s/ihwRjr6OtL43zJLzOFQ+/zciONEd+sp7LwrfOCnyukPSsg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.1/css/bootstrap.css">
<script type="text/javascript">
<?php if (getenv('GOOGLEANALYTICS_ACCOUNT')) { ?>
var _gaq = _gaq || [];
_gaq.push(['_setAccount', <?= json_encode(getenv('GOOGLEANALYTICS_ACCOUNT')) ?>]);
_gaq.push(['_trackPageview']);

(function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
})();
<?php } ?>
</script>
</head>
<body class="@yield('body_class')">
<nav class="navbar navbar-expand-lg bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">g0v 成就系統</a>
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
            <a href="/" class="nav-link">回首頁</a>
            </li>
            @if (!session('login_id'))
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                登入 
                </a>
                <ul class="dropdown-menu">
                    <li>
                    <a href="/_/user/slacklogin" class="dropdown-item">Slack 登入</a>
                    </li>
                    <li>
                    <a href="/_/user/googlelogin" class="dropdown-item">Google 登入</a>
                    </li>
                    <li>
                    <a href="/_/user/githublogin" class="dropdown-item">Github 登入</a>
                    </li>
                </ul>
            </li>
            @else
                <span class="navbar-text">Hi! {{ session('login_name') }}</span>
                @if ($user = $User::findByLoginID(session('login_id')))
                    <li class="nav-item">
                    <a href="/<?= urlencode($user->name) ?>" class="nav-link">瀏覽我的頁面</a>
                    </li>
                    <li class="nav-item">
                    <a href="/_/user/edit" class="nav-link">修改個人頁面</a>
                    </li>
                @else
                    <li class="nav-item">
                    <a href="/_/user/new" class="nav-link">建立個人頁面</a>
                    </li>
                @endif
                <li class="nav-item">
                <a href="/_/user/logout" class="nav-link">登出</a>
                </li>
            @endif
        </ul>
    </div>
</nav>
<div class="container-fluid">
    @yield('content')
<form method="post" id="post-form">
    @csrf
</form>

<script type="text/javascript"><!--
$(function(){
    $('body').on('submit', '.ajax-form', function(e){
        e.preventDefault();
        var formdata = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: formdata,
            processData: false,
            contentType: false,
            success: function(ret){
                if (ret.error) {
                    alert(ret.message);
                    return;
                }
                document.location = document.location;
            }
        });
    });

    $('body').on('click', '.post-link', function(e){
        e.preventDefault();

        if ($(this).is('.confirm')) {
            if (!confirm($(this).attr('data-confirm'))) {
                return;
            }
        }

        var url;
        if ($(this).attr('href')) {
            url = $(this).attr('href');
        } else if ($(this).attr('data-link')) {
            url = $(this).attr('data-link');
        }

        if ($(this).attr('data-success-url')) {
            var done_url = $(this).attr('data-success-url');
            $.ajax({
                url: url,
                type: $(this).attr('data-method') || 'post',
                processData: false,
                contentType: false,
                data: new FormData($('#post-form')[0]),
                success: function(ret){
                    if (ret.error) {
                        alert(ret.message);
                        return;
                    }
                    document.location = done_url;
                }
            });
            return;
        }

        $('#post-form').attr('action', url).submit();
    });
});
//--></script>

</div><!-- .container -->
</body>
</html>

