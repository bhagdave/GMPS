<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Matrix\Matrix;

class MatrixServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Matrix::class, function ($app) {
            return new Matrix(config('matrix')['domain']);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    public function provides(){
        return [Matrix::class];
    }
}
