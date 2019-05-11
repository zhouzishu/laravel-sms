<?php

$routeAttr = config('laravel-sms.route', []);
unset($routeAttr['enable']);

$attributes = array_merge([
    'prefix' => 'laravel-sms',
], $routeAttr);

Route::group($attributes, function () {
    Route::get('info', 'Zhouzishu\LaravelSms\SmsController@getInfo');
    Route::post('verify-code', 'Zhouzishu\LaravelSms\SmsController@postSendCode');
});
