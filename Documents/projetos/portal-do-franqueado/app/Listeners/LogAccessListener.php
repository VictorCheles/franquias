<?php

namespace App\Listeners;

use Request;
use App\Models\Historiae\AccessLog;
use Illuminate\Support\Facades\Auth;

class LogAccessListener
{
    /**
     * Handle the event.
     *
     * @param   \Illuminate\Http\Request  $request
     * @param   \Illuminate\Http\Response $response
     * @return  void
     */
    public function handle($request, $response)
    {
        AccessLog::create([
            'ip' => $request->ip(),
            'domain' => Request::root(),
            'url' => str_limit($request->getRequestUri(), 200),
            'status' => $response->getStatusCode(),
            'method' => $request->getMethod(),
            'user_id' => Auth::id(),
        ]);
    }
}
