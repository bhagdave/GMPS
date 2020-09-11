<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\URL;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function getSignedUrl($route, $uuid, $email){
        return  URL::temporarySignedRoute($route, 
            now()->addDays(7),
            [
                'uuid' => $uuid, 
                'email' => $email
            ]
        );
    }

}
