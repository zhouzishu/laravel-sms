<?php


return [
    /*
    |--------------------------------------------------------------------------
    | 内置路由
    |--------------------------------------------------------------------------
    |
    | 如果是 web 应用建议 middleware 为 ['web', ...]
    | 如果是 api 应用建议 middleware 为 ['api', ...]
    |
    */
    'route' => [
        'enable'     => true,
        'prefix'     => 'laravel-sms',
        'middleware' => ['web'],
    ],

    /*
    |--------------------------------------------------------------------------
    | EasySMS配置
    |--------------------------------------------------------------------------
    |
    | 详情配置项参见：https://github.com/overtrue/easy-sms
    |
    */
    'easy_sms' => [
        // HTTP 请求的超时时间（秒）
        'timeout' => 5.0,

        // 默认发送配置
        'default' => [
            // 网关调用策略，默认：顺序调用
            'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,

            // 默认可用的发送网关
            'gateways' => [
                'yunpian', 'aliyun',
            ],
        ],
        // 可用的网关配置
        'gateways' => [
            'errorlog' => [
                'file' => '/tmp/easy-sms.log',
            ],
            'yunpian' => [
                'api_key' => '824f0ff2f71cab52936axxxxxxxxxx',
            ],
            'aliyun' => [
                'access_key_id' => 'xxxx',
                'access_key_secret' => 'xxxx',
                'sign_name' => '阿里云短信测试专用',
                'code_template_id' => 'SMS_802xxx',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | 请求间隔
    |--------------------------------------------------------------------------
    |
    | 单位：秒
    |
    */
    'interval' => 60,

    /*
    |--------------------------------------------------------------------------
    | 数据验证管理
    |--------------------------------------------------------------------------
    |
    | 设置从客户端传来的需要验证的数据字段(`field`)
    |
    | - isMobile    是否为手机号字段
    | - enable      是否开启验证
    | - default     默认静态验证规则
    | - staticRules 静态验证规则
    |
    */
    'validation' => [
        'mobile' => [
            'isMobile'    => true,
            'enable'      => true,
            'default'     => 'mobile_required',
            'staticRules' => [
                'mobile_required'     => 'required|zh_mobile',
                'check_mobile_unique' => 'required|zh_mobile|unique:users,mobile',
                'check_mobile_exists' => 'required|zh_mobile|exists:users',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | 验证码管理
    |--------------------------------------------------------------------------
    |
    | - length        验证码长度
    | - validMinutes  验证码有效时间长度，单位为分钟
    | - repeatIfValid 如果原验证码还有效，是否重复使用原验证码
    | - maxAttempts   验证码最大尝试验证次数，超过该数值验证码自动失效，0或负数则不启用
    |
    */
    'code' => [
        'length'        => 5,
        'validMinutes'  => 5,
        'repeatIfValid' => false,
        'maxAttempts'   => 0,
    ],

    /*
    |--------------------------------------------------------------------------
    | 验证码短信通用内容
    |--------------------------------------------------------------------------
    |
    | 如需缓存配置，则需使用 `Zhouzishu\LaravelSms\SmsManger::closure($closure)` 方法进行配置
    | 如果已在EasySms配置项配置signature或者使用模板id方式发送则不需要
    |
    */
    'content' => '您的验证码是%s。如非本人操作，请忽略本短信',

    /*
    |--------------------------------------------------------------------------
    | 验证码短信模版
    |--------------------------------------------------------------------------
    |
    | 每项数据的值可以为以下三种之一:
    |
    | - 字符串/数字
    |   如: 'YunTongXun' => '短信模版id'
    |
    | - 数组
    |   如: 'Alidayu' => ['短信模版id', '语音模版id'],
    |
    | - 匿名函数
    |   如: 'YunTongXun' => function ($input, $type) {
    |           return $input['isRegister'] ? 'registerTempId' : 'commonId';
    |       }
    |
    | 如需缓存配置，则需使用 `Zhouzishu\LaravelSms\SmsManger::closure($closure)` 方法对匿名函数进行配置
    |
    */
    'templates' => [
    ],

    /*
    |--------------------------------------------------------------------------
    | 模版数据管理
    |--------------------------------------------------------------------------
    |
    | 每项数据的值可以为以下两种之一:
    |
    | - 基本数据类型
    |   如: 'minutes' => 5
    |
    | - 匿名函数（如果该函数不返回任何值，即表示不使用该项数据）
    |   如: 'serialNumber' => function ($code, $minutes, $input, $type) {
    |           return $input['serialNumber'];
    |       }
    |   如: 'hello' => function ($code, $minutes, $input, $type) {
    |           //不返回任何值，那么hello将会从模版数据中移除 :)
    |       }
    |
    | 如需缓存配置，则需使用 `Zhouzishu\LaravelSms\SmsManger::closure($closure)` 方法对匿名函数进行配置
    |
    */
    'data' => [
        'code' => function ($code) {
            return $code;
        },
        'minutes' => function ($code, $minutes) {
            return $minutes;
        },
    ],

    /*
    |--------------------------------------------------------------------------
    | 存储系统配置
    |--------------------------------------------------------------------------
    |
    | driver:
    | 存储方式,是一个实现了'Zhouzishu\LaravelSms\Storage'接口的类的类名,
    | 内置可选的值有'Zhouzishu\LaravelSms\SessionStorage'和'Zhouzishu\LaravelSms\CacheStorage',
    | 如果不填写driver,那么系统会自动根据内置路由的属性(route)中middleware的配置值选择存储器driver:
    | - 如果中间件含有'web',会选择使用'Zhouzishu\LaravelSms\SessionStorage'
    | - 如果中间件含有'api',会选择使用'Zhouzishu\LaravelSms\CacheStorage'
    |
    | prefix:
    | 存储key的prefix
    |
    | 内置driver的个性化配置:
    | - 在laravel项目的'config/session.php'文件中可以对'Zhouzishu\LaravelSms\SessionStorage'进行更多个性化设置
    | - 在laravel项目的'config/cache.php'文件中可以对'Zhouzishu\LaravelSms\CacheStorage'进行更多个性化设置
    |
    */
    'storage' => [
        'driver' => '',
        'prefix' => 'laravel_sms',
    ],

    /*
    |--------------------------------------------------------------------------
    | 是否数据库记录发送日志
    |--------------------------------------------------------------------------
    |
    | 若需开启此功能,需要先生成一个内置的'laravel_sms'表
    | 运行'php artisan migrate'命令可以自动生成
    |
    */
    'dbLogs' => false,

    /*
    |--------------------------------------------------------------------------
    | 验证码模块提示信息
    |--------------------------------------------------------------------------
    |
    */
    'notifies' => [
        // 频繁请求无效的提示
        'request_invalid' => '请求无效，请在%s秒后重试',

        // 验证码短信发送失败的提示
        'sms_sent_failure' => '短信验证码发送失败，请稍后重试',

        // 验证码短信发送成功的提示
        'sms_sent_success' => '短信验证码发送成功，请注意查收',
    ],
];
