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
}
