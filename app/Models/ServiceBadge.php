<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ServiceBadge extends Model 
{
    protected $table = 'service_badge';

    public function service()
    {
        return $this->hasOne(Service::class, 'id', 'service_id');
    }

    public static function getBadgeRank($badges)
    {
        $ids = $badges->pluck('id')->toArray('id');
        $sql = "SELECT id,
            (SELECT COUNT(*) FROM service_badge WHERE main.service_id = service_id AND main.badge_hash = badge_hash) AS total,
            (SELECT COUNT(*) FROM service_badge WHERE main.service_id = service_id AND main.badge_hash = badge_hash AND main.badge_time > badge_time) AS rank
            FROM service_badge AS main WHERE id IN (" . implode(',', $ids) . ")";
        $ranks = new \StdClass;
        foreach (DB::select($sql) as $row) {
            $ranks->{$row->id} = [$row->rank + 1, $row->total];
        }
        return $ranks;
    }

    public function getData()
    {
        return json_decode($this->data);
    }

    public static function getBadgeList($service_id, $hash)
    {
        $ret = [];
        $prev_time = null;
        $rank = 1;
        $sql = sprintf("SELECT badge_time, service_user.data->>'name' AS name, \"user\".name AS user_id, service_user.id AS service_user_id
            FROM service_badge
            JOIN service_user ON service_badge.service_user = service_user.id
            LEFT JOIN \"user\" ON \"user\".data->'service_users' @> TO_JSONB(service_user.id)
            WHERE service_user.service_id = %d AND badge_hash = %d ORDER BY badge_time ASC", intval($service_id), intval($hash));
        foreach (DB::select($sql) as $row) {
            $obj = new \StdClass;
            $obj->badge_time = $row->badge_time;
            $obj->name = $row->name;
            $obj->user_id = $row->user_id;
            $obj->service_user_id = $row->service_user_id;
            if (is_null($prev_time) or $prev_time != $row->badge_time) {
                $rank = count($ret) + 1;
                $prev_time = $row->badge_time;
            }
            $obj->rank = $rank;
            $ret[] = $obj;
        }
        return $ret;
    }
}
