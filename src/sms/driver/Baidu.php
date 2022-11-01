<?php
// +----------------------------------------------------------------------
// | ThinkSms [sms package for thinkphp]
// +----------------------------------------------------------------------
// | ThinkPHP短信扩展
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: axguowen <axguowen@qq.com>
// +----------------------------------------------------------------------

namespace axguowen\sms\driver;

include __DIR__ . '/baidu/BaiduBce.phar';

use axguowen\sms\Platform;
use BaiduBce\BceClientConfigOptions;
use BaiduBce\Services\Sms\SmsClient;

class Baidu extends Platform
{
    /**
     * 平台句柄
     * @var \BaiduBce\Services\Sms\SmsClient
     */
    protected $handler;

	/**
     * 平台配置参数
     * @var array
     */
    protected $options = [
        // 公钥
        'access_key' => '',
        // 私钥
        'secret_key' => '',
        // 模板ID
        'template_id' => '',
        // 签名ID
        'signature_id' => '',
        // 服务接入点
        'endpoint' => '',
    ];

	/**
     * 架构函数
     * @access public
     * @param array $options 配置参数
     */
    public function __construct($options = [])
    {
        if (!empty($options)) {
            $this->options = array_merge($this->options, $options);
        }
        // 认证配置
        $config = [
            BceClientConfigOptions::PROTOCOL => 'https',
            BceClientConfigOptions::REGION => 'bj',
            BceClientConfigOptions::CREDENTIALS => [
                'ak' => $this->options['access_key'],
                'sk' => $this->options['secret_key']
            ],
            BceClientConfigOptions::ENDPOINT => $this->options['endpoint'] ?: 'smsv3.bj.baidubce.com',
        ];
		// 实例化短信模型
		$this->handler = new SmsClient($config);
    }

	/**
     * 发送短信
     * @access public
     * @param array $data 短信变量
     * @param string $template 临时指定模板
     * @return mixed
     */
	public function send($data = [], $template = null)
	{
        // 获取模板
        $template = is_null($template) ? $this->options['template_id'] : $template;

        try{
            // 发送短信
            $sendMessage = $this->handler->sendMessage(implode(',', $this->mobiles), $this->options['signature_id'], $template, $data);
            // 发送成功
            if(isset($sendMessage->code) && $sendMessage->code == '1000'){
                return true;
            }
            // 获取错误信息
            $errMessage = isset($sendMessage->message) ? $sendMessage->message : json_encode($sendMessage);
            // 获取错误代码
            $errCode = isset($sendMessage->code) ? $sendMessage->code : 'error';
            // 手动抛出异常
            throw new \Exception($errMessage . ', ErrorCode:' . $errCode, 400);
        }
        // 异常捕获
        catch (\Exception $e) {
            // 如果开启调试模式
            if(\think\facade\App::isDebug()){
                // 手动抛出异常
                throw new \think\Exception($e->getMessage(), $e->getCode());
            }
        }
        
		// 返回失败
		return false;
	}
}