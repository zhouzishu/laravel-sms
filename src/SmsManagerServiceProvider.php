<?php

namespace Zhouzishu\LaravelSms;

use Illuminate\Support\ServiceProvider;
use Overtrue\EasySms\EasySms;

class SmsManagerServiceProvider extends ServiceProvider
{
    /**
     * 启动服务
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/laravel-sms.php' => config_path('laravel-sms.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/../migrations/' => database_path('/migrations'),
        ], 'migrations');

        if (config('laravel-sms.route.enable', true)) {
            require __DIR__ . '/routes.php';
        }

        require __DIR__ . '/validations.php';
    }

    /**
     * 注册服务
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/laravel-sms.php', 'laravel-sms');

        $this->app->singleton('Zhouzishu\\LaravelSms\\SmsManager', function ($app) {
            $token = $app->request->header('access-token', null);
            if (empty($token)) {
                $token = $app->request->input('access_token', null);
            }
            $input = $app->request->all();

            return new SmsManager(new EasySms(config('laravel-sms.easy_sms')), $token, $input);
        });
    }
}
