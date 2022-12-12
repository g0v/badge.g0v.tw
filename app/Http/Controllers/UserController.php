<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ServiceUser;
use App\Models\ServiceBadge;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show($user)
    {
        if (!$u = User::where('name', $user)->first()) {
            return response()->view('errors.404', [], 404);
        }
        return view('user/show', [
            'User' => User::class,
            'ServiceUser' => ServiceUser::class,
            'ServiceBadge' => ServiceBadge::class,
            'user' => $u,
        ]);
    }

    public function index()
    {
        if ($user_id = session('user_id')) {
            return redirect('/_/user/edit');
        }

        if (!$login_id = session('login_id')) {
            return redirect('/');
        }

        if ($user = User::findByLoginID($login_id)) {
            session(['user_id' => $user->id]);
            return redirect('/_/user/edit');
        }

        $ids = session('ids');
        if (!$users = ServiceUser::searchByIds($ids)) {
            return view('alert')->with([
                'message' => '您目前還未有任何成就可以領取，請期待下一版本改版',
                'next' => '/',
            ]);
        }

        return redirect('/_/user/new');

    }
}
