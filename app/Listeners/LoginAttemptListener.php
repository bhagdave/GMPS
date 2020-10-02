<?php

namespace App\Listeners;

use App\Events\auth.attempt;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LoginAttemptListener
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
     * @param  auth.attempt  $event
     * @return void
     */
    public function handle(auth.attempt $event)
    {
        //
    }
}
