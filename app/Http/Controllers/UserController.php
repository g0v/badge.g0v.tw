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

    public function edit()
    {
        if (!$user_id = session('user_id')) {
            return redirect('/_/user/');
        }

        if (!$user = User::find($user_id)) {
            return redirect('/_/user/new');
        }

        return view('user/edit', [
            'ServiceUser' => ServiceUser::class,
            'user' => $user,
            'User' => User::class,
            'ServiceBadge' => ServiceBadge::class,
        ]);
	}

    public function editPost()
    {
        if (!$user_id = session('user_id')) {
            return redirect('/_/user/');
        }

        if (!$user = User::find($user_id)) {
            return redirect('/_/user/new');
        }

		$data = json_decode($user->data);
		$data->info = $_POST['info'];
		$user->data = json_encode($data);
		$user->save();
		return view('alert')->with([
			'message' => '更新完成',
			'next' => '/_/user/edit',
		]);
    }

	public function delete()
	{
        if (!$user_id = session('user_id')) {
            return redirect('/_/user');
        }
        $user = User::find($user_id);
        if ($_POST['name'] != $user->name) {
            return redirect('/_/user/edit');
        }
        $user->delete();
        return redirect('/_/user/logout');
	}

	public function newuser()
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

		$prefixs = ServiceUser::getUserIdPrefixByIds($ids);
        return view('user/new', [
            'ServiceUser' => ServiceUser::class,
            'User' => User::class,
            'login_id' => $login_id,
            'ids' => $ids,
            'prefixs' => $prefixs,
        ]);
	}

	public function newuserPost()
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
			return $this->alert('您目前還未有任何成就可以領取，請期待下一版本改版', '/');
		}

		if (strlen($_POST['id']) < 2) {
			return $this->alert('id 太短', '/_/user/new');
		}
		if (strlen($_POST['id']) > 16) {
			return $this->alert('id 太長', '/_/user/new');
		}
		$id = $_POST['id'];
		$prefixs = ServiceUser::getUserIdPrefixByIds($ids);
		if ($prefixs) {
			$prefixs = array_filter($prefixs, function($s) use ($id){
				return strpos($id, $s) === 0;
			});
			if (count($prefixs) == 0) {
				return $this->alert("id 必須以 " . implode(' 或 ', $prefixs) . " 開頭", '/_/user/new/');
			}
		}

		try {
			$d = new \StdClass;
			if ($avatar = session('avatar')) {
				$d->avatar = $avatar;
			}
			$u = User::insert([
				'name' => $_POST['id'],
				'ids' => json_encode($ids),
				'data' => json_encode($d),
			]);
			$u->updateServiceUsers();
		} catch (Pix_Table_DuplicateException $e) {
			return $this->alert('代號已經被使用了，請再更換代號', '/_/user/new');
		}

		return $this->alert('建立成功', '/_/user/edit');

	}

}
