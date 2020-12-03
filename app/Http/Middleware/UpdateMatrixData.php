<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Matrix\Matrix;
use Illuminate\Support\Facades\Log;

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
        Log::info("--------------------My Middleware----------------");
        $this->matrix->updateSession();
        return $next($request);
    }
}
