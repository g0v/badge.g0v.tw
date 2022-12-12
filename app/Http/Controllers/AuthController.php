<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function logout()
    {
        session(['login_id' => null]);
        session(['login_name' => null]);
        session(['ids' => null]);
        session(['avatar' => null]);
        session(['user_id' => null]);
        return redirect('/');
    }

    public function redirectToGoogle()
    {
        session(['next' => $_GET['next'] ?? '']);
        return Socialite::driver('google')
            ->scopes(['email'])
            ->with([
                'access_type' => 'offline',
            ])
            ->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $login_info = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return view('alert')->with([
                'message' => 'login failed: ' . $e->getMessage(),
                'next' => '/',
            ]);
        }
        $next = session('next');

        if (!$login_info->email or !$login_info->user['email_verified']) {
            return view('alert')->with([
                'message' => 'login failed',
                'next' => '/',
            ]);
        }
        $email = $login_info->email;
        if ($login_info->avatar) {
            $login_info->avatar = preg_replace('/=s96-c$/', '=s1000', $login_info->avatar);
            session(['avatar' => $login_info->avatar]);
        } else {
            session(['avatar' => '']);
        }

        session(['login_id' => $email]);
        session(['login_name' =>  explode('@', $email)[0]]);
        $ids = [];
        $ids[] = $email;
        session(['ids' => $ids]);
        if ($next) {
            return redirect($next);
        } else {
            return redirect('/_/user/');
        }
    }

    public function redirectToGithub()
    {
        session(['next' => $_GET['next'] ?? '']);
        return Socialite::driver('github')
            ->scopes(['email'])
            ->redirect();
    }

    public function handleGithubCallback()
    {
        try {
            $login_info = Socialite::driver('github')->user();
        } catch (\Exception $e) {
            return view('alert')->with([
                'message' => 'login failed: ' . $e->getMessage(),
                'next' => '/',
            ]);
        }
        $next = session('next');

        if (!$login_info->email) {
            return view('alert')->with([
                'message' => 'login failed',
                'next' => '/',
            ]);
        }
        $email = $login_info->email;
        if ($login_info->avatar) {
            $login_info->avatar = preg_replace('/=s96-c$/', '=s1000', $login_info->avatar);
            session(['avatar' => $login_info->avatar]);
        } else {
            session(['avatar' => '']);
        }

        session(['login_id' => "github://{$login_info->user['id']}"]);
        session(['login_name' => $login_info->nickname]);
        $ids = [];
        $ids[] = $email;
        $ids[] = "github://{$login_info->user['id']}";
        session(['ids' => $ids]);
        if ($next) {
            return redirect($next);
        } else {
            return redirect('/_/user/');
        }
    }

    public function redirectToSlack()
    {
        session(['next' => $_GET['next'] ?? '']);
        return Socialite::driver('slack')
            ->scopes(['identity.basic', 'identity.avatar', 'identity.email'])
            ->redirect();
    }

    public function handleSlackCallback()
    {
        try {
            $login_info = Socialite::driver('slack')->user();
        } catch (\Exception $e) {
            return view('alert')->with([
                'message' => 'login failed: ' . $e->getMessage(),
                'next' => '/',
            ]);
        }
        $next = session('next');

        if (!$login_info->email) {
            return view('alert')->with([
                'message' => 'login failed',
                'next' => '/',
            ]);
        }
        $email = $login_info->email;
        if ($login_info->avatar) {
            session(['avatar' => $login_info->avatar]);
        } else {
            session(['avatar' => '']);
        }

        session(['login_id' => "slack://{$login_info->id}"]);
        session(['login_name' => $login_info->name]);
        $ids = [];
        $ids[] = "slack://{$login_info->id}";
        $ids[] = $email;
        session(['ids' => $ids]);
        if ($next) {
            return redirect($next);
        } else {
            return redirect('/_/user/');
        }
    }
}
