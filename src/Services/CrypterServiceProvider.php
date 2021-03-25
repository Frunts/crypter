<?php
namespace Frunts\SimpleCrypter\Services;

use Frunts\SimpleCrypter\Crypter;
use Illuminate\Support\ServiceProvider;

class CrypterServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__."/../config/simpleCrypter.php" => config_path('simpleCrypter.php'),
        ]);
    }

    public function register()
    {
        $this->app->singleton('crypter', function($app){
            return new Crypter($app['config']);
        });
    }
}