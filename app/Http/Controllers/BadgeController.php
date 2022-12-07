<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceBadge;
use Illuminate\Http\Request;

class BadgeController extends Controller
{
    public function show()
    {
        if (!$service = Service::find(intval($_GET['service_id']))) {
            return response()->view('errors.404', [], 404);
        }

        if (!$badge = ServiceBadge::where('service_id', $service->id)->where('badge_hash', intval($_GET['hash']))->first()) {
            return response()->view('errors.404', [], 404);
        }
        return view('badge/show', [
            'ServiceBadge' => ServiceBadge::class,
            'service' => $service,
            'badge' => $badge,
        ]);
    }
}
