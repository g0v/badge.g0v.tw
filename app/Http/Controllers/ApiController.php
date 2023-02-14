<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ServiceUser;
use App\Models\ServiceBadge;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function me()
    {
        if (!$user_id = session('user_id')) {
            return ['login' => false];
        }

        if (!$login_id = session('login_id')) {
            return ['login' => false];
        }

        if (!$user = User::findByLoginID($login_id)) {
            return ['login' => false];
        }
        return ['login' => true, 'name' => $user->name];
    }
}
