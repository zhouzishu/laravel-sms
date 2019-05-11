<?php

namespace Zhouzishu\LaravelSms;

use Overtrue\EasySms\Message as BaseMessage;
use Overtrue\EasySms\Contracts\GatewayInterface;

class Message extends BaseMessage
{
    /**
     * @var
     */
    protected $code;
    /**
     * @var
     */
    protected $minutes;
    protected $data;

    /**
     * CodeMessage constructor.
     *
     * @param $code
     * @param $minutes
     */
    public function __construct($code, $minutes, array $data = [])
    {
        $this->code = $code;
        $this->minutes = $minutes;
        $this->data = $data;
    }

    /**
     * 定义直接使用内容发送平台的内容.
     *
     * @param GatewayInterface|null $gateway
     *
     * @return string
     */
    public function getContent(GatewayInterface $gateway = null)
    {
        if (! empty($this->data) && isset($this->data['content'])) {
            $content = $this->data['content'];
        } else {
            $content = config('laravel-sms.content');
        }

        return vsprintf($content, [$this->code, $this->minutes]);
    }

    /**
     * 定义使用模板发送方式平台所需要的模板 ID.
     *
     * @param GatewayInterface|null $gateway
     *
     * @return mixed
     */
    public function getTemplate(GatewayInterface $gateway = null)
    {
        $classname = get_class($gateway);
        if ($pos = strrpos($classname, '\\')) {
            $classname = substr($classname, $pos + 1);
        }
        if ($classname) {
            $classname = strtolower(str_replace('Gateway', '', $classname));
        }
        if (! empty($this->data) && isset($this->data['template'])) {
            return $this->data['template'];
        } else {
            return config('laravel-sms.easy_sms.gateways.'.$classname.'.code_template_id');
        }
    }

    /**
     * @param GatewayInterface|null $gateway
     *
     * @return array
     */
    public function getData(GatewayInterface $gateway = null)
    {
        if (! empty($this->data) && isset($this->data['data']) && is_array($this->data['data'])) {
            $data = $this->data['data'];
        } else {
            $data = array_filter(config('laravel-sms.easy_sms'));
        }

        return array_merge($data, ['code' => $this->code]);
    }
}
