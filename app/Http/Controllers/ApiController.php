<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ServiceUser;
use App\Models\ServiceBadge;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    function cors_header($methods = 'GET')
    {
        if (getenv('CORS_Origin')) {
            foreach (explode(',', getenv('CORS_Origin')) as $origin) {
                if (array_key_exists('HTTP_ORIGIN', $_SERVER) and $_SERVER['HTTP_ORIGIN'] == $origin) {
                    header('Access-Control-Allow-Origin: ' . $origin);
                    header('Access-Control-Allow-Methods: ' . $methods);
                    header('Access-Control-Allow-Credentials: true');
                    break;
                }
            }
        }
    }
    public function me()
    {
        $this->cors_header();

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
