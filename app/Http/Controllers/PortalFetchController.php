<?php

namespace App\Http\Controllers;

use App\Services\PortalFetchService;

class PortalFetchController extends Controller
{
    public function fetch(PortalFetchService $service)
    {
        $ok = $service->fetch(auth()->user());

        return back()->with('status', $ok
            ? 'Your details have been fetched from the student portal.'
            : 'No matching record was found in the student portal.');
    }
}