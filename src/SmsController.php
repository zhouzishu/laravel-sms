<?php

namespace Zhouzishu\LaravelSms;

use SmsManager as Manager;
use Illuminate\Routing\Controller;

class SmsController extends Controller
{
    public function postSendCode()
    {
        $res = Manager::validateSendable();
        if (! $res['success']) {
            return response()->json($res);
        }

        $res = Manager::validateFields();
        if (! $res['success']) {
            return response()->json($res);
        }

        $res = Manager::requestVerifySms();

        return response()->json($res);
    }

    public function getInfo()
    {
        $html = '<meta charset="UTF-8"/><h2 align="center" style="margin-top: 30px;margin-bottom: 0;">Laravel Sms</h2>';
        $html .= '<p><a href="https://github.com/zhouzishu/laravel-sms" target="_blank">laravel-sms源码</a>托管在GitHub，欢迎你的使用。如有问题和建议，欢迎提供issue。</p>';
        $html .= '<hr>';
        $html .= '<p>你可以在调试模式(设置config/app.php中的debug为true)下查看到存储在存储器中的验证码短信/语音相关数据:</p>';
        echo $html;
        if (config('app.debug')) {
            dump(Manager::retrieveAllData());
        } else {
            echo '<p align="center" style="color: red;">现在是非调试模式，无法查看调试数据</p>';
        }
    }
}
