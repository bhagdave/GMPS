<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Attempting;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use App\Matrix\Matrix;
use App\Matrix\UserData;
use App\Matrix\UserSession;
use App\User;

class LoginAttempt
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
     * @param  Attempting  $event
     * @return void
     */
    public function handle(Attempting $event)
    { 
        Log::debug('Handling login attempt');
        $user = $this->getUser($event->credentials['email']);
        if (isset($user)){
            if (isset($user->matrix_user_id)){
                $matrixData = $this->matrixSession->login($user->matrix_user_id, $event->credentials['password']);
                if (!isset($matrixData)){
                    Log::error("Error in login user for " . $event->credentials['email'] . " Error:NO Matrix Server");
                    return;
                }
            } else {
                $matrixData = $this->createMatrixUser($user, $event->credentials['email'], $event->credentials['password']);
                if (isset($matrixData['errcode'])){
                    Log::error("Error creating user for " . $event->credentials['email'] . $matrixData['error']);
                    return;
                }
                if (isset($matrixData)){
                    $user->matrix_user_id = $matrixData['user_id'];
                    $user->matrix_device_id = $matrixData['device_id'];
                    $user->save();
                } else {
                    Log::error('Matrix not responding to us.');
                    return;
                }
            }
            Log::debug(print_r($matrixData, true));
            session(['matrix_access_token' => $matrixData['access_token'] ]);
        }
    }

    private function getUser($email){
        return User::where('email', $email)->first();
    }

    private function createMatrixUser($user, $password){
        $userData = new UserData($this->matrix);
        $name = str_replace(' ', '', $user->name);
        $regData = $userData->register($name, $password);
        return $regData;
    }
}
