<?php

namespace Zhouzishu\LaravelSms\Facades;

use Illuminate\Support\Facades\Facade;

class SmsManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Zhouzishu\\LaravelSms\\SmsManager';
    }
}
