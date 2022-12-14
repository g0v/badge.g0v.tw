<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceUser extends Model 
{
    protected $table = 'service_user';

    public function service()
    {
        return $this->hasOne(Service::class, 'id', 'service_id');
    }

    public function badges()
    {
        return $this->hasMany(ServiceBadge::class, 'service_user');
    }


    public static function searchByIds($ids)
    {
        if (!$ids) {
            return;
        }
        $terms = array_map(function($id) {
            return sprintf("(data->'hash_ids' @> '\"%s\"')", md5($id . 'g0vg0v'));
        }, $ids);

        $users = [];

        foreach (ServiceUser::whereRaw(implode(' OR ', $terms))->get() as $service_user) {
            $users[$service_user->id] = $service_user;
        }

        return array_values($users);
    }

    public function getLink()
    {
        $data = $this->getData();
        $service = $this->service->service_id;
        if ($service == 'hackpad') {
            return null;
        }
        if ($service == 'github') {
            if (preg_match('#^[0-9]+$#', $this->user_id)) {
                return null;
            }
            return 'https://github.com/' . $this->user_id;
        }
        if ($service == 'hackmd') {
            $id = rtrim(base64_encode(hex2bin(str_replace('-', '', $this->user_id))), '=');
            $id = str_replace('+', '-', $id);
            $id = str_replace('/', '_', $id);
            return 'https://g0v.hackmd.io/@' . $id;
        }
        if ($service == 'slack') {
            return 'https://g0v-tw.slack.com/team/' . $this->user_id;
        }
    }

    public function getData()
    {
        return json_decode($this->data);
    }

    public static function getUserIdPrefixByIds($ids)
    {
        $users = self::searchByIds($ids);
        if (!$users) {
            return [];
        }

        $names = [];
        foreach ($users as $u) {
            $n = preg_replace('#\s#', '', strtolower($u->getData()->name));
            if ($n) {
                $names[] = $n;
            }
        }
        return array_unique($names);
    }

}
