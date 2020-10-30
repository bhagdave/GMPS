<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Login;
use App\Matrix\Matrix;
use App\Matrix\UserData;
use App\Matrix\UserSession;

class LoginListener
{
    private $matrixSession;
    private $matrix;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Matrix $matrix)
    {
        $this->matrixSession = new UserSession($matrix);
        $this->matrix = $matrix;
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        Log::debug('Handling LOGIN');
        if (isset($event->user)){
            Log::debug('Got the user');
            $this->matrixSession->sync($event->user);
        }
    }
}
