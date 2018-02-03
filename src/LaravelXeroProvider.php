<?php

namespace Batmahir\Laravelxero;

//use Illuminate\Support\ServiceProvider;
//use Batmahir\LaravelXero\Helper;
use Batmahir\Laravelxero\Helper;

class LaravelXeroProvider //extends ServiceProvider
{
    use Helper;

    public function __construct()
    {
        echo "<br>construct<br>";
        echo $this->getNonce();

    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    //Batmahir\LaravelXero\LaravelXeroProvider
}
