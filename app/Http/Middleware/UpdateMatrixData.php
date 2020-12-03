<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Matrix\Matrix;
use Illuminate\Support\Facades\Log;

/**
*
*        This is to force the session data into the matrix object on each request.
*        The Matrix object is created by a service provider but the session is not available
*        in a service provider. Hence this middleware
*/
class UpdateMatrixData
{
    private $matrix;

    public function __construct(Matrix $matrix){
        $this->matrix = $matrix;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $this->matrix->updateSession();
        return $next($request);
    }
}
