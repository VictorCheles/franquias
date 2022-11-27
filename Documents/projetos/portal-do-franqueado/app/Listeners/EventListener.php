<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;

class EventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        //session()->put('recursos', Auth()->user()->grupoACL->recursos);
    }
}
