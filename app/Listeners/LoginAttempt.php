<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Attempting;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use App\Matrix\Matrix;
use App\Matrix\UserSession;
use App\User;

class LoginAttempt
{

    private $matrixSession;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Matrix $matrix)
    {
        $this->matrixSession = new UserSession($matrix);
    }

    /**
     * Handle the event.
     *
     * @param  Attempting  $event
     * @return void
     */
    public function handle(Attempting $event)
    { 
        Log::debug('Handling login attempt');
        $user = $this->getUser($event->credentials['email']);
        if (isset($user->synapse_user_id)){
            $matrixData = $this->matrixSession->login($user->synapse_user_id, $event->credentials['password']);
            Log::debug(print_r($matrixData, true));
            session(['synapse_access_token' => $matrixData['access_token'] ]);
        }
    }

    private function getUser($email){
        return User::where('email', $email)->first();
    }
}
