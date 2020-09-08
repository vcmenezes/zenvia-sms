<?php

namespace Menezes\ZenviaSms\Providers;

use Illuminate\Support\ServiceProvider;
use Menezes\ZenviaSms\Commands\SendSmsTest;
use Menezes\ZenviaSms\Services\ZenviaService;

class ZenviaSmsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('zenvia', static function($app){
            $account = config('zenvia.account', env('ZENVIA_ACCOUNT'));
            $password = config('zenvia.password', env('ZENVIA_PASSWORD'));
            return new ZenviaService($account, $password);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                SendSmsTest::class,
            ]);
        }

        $configPath = $this->getConfigDir();
        $publishPath = base_path('config/zenvia.php');
        $this->publishes([$configPath => $publishPath], 'config');

    }

    private function getConfigDir(): string
    {
        return dirname(__DIR__).'/../config/config.php';
    }

    public function provides()
    {
        return ['zenvia'];
    }
}
