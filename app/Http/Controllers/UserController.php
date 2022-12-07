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
}
