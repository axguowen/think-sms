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

use axguowen\sms\Platform;

class Aliyun extends Platform
{
    /**
     * 平台句柄
     * @var \AlibabaCloud\SDK\Dysmsapi\V20170525\Dysmsapi
     */
    protected $handler;

	/**
     * 平台配置参数
     * @var array
     */
    protected $options = [
        // 公钥
        'access_id' => '',
        // 私钥
        'access_secret' => '',
        // 模板ID
        'template_id' => '',
		// 已审核的签名
		'sign_name' => '',
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

        // 实例化认证对象
		$config = new \Darabonba\OpenApi\Models\Config([
            'accessKeyId' => $this->options['access_id'],
            'accessKeySecret' => $this->options['access_secret'],
        ]);
        // 访问的域名
        $config->endpoint = empty($this->options['endpoint']) ? 'dysmsapi.aliyuncs.com' : $this->options['endpoint'];

		// 实例化短信模型
		$this->handler = new \AlibabaCloud\SDK\Dysmsapi\V20170525\Dysmsapi($config);;
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

        // 实例化请求类
        $sendSmsRequest = new \AlibabaCloud\SDK\Dysmsapi\V20170525\Models\SendSmsRequest([
            'phoneNumbers' => implode(',', $this->mobiles),
            'signName' => $this->options['sign_name'],
            'templateCode' => $template,
            'templateParam' => json_encode($data),
        ]);

        try{
            // 发送短信
            $sendMessage = $this->handler->sendSms($sendSmsRequest)->toMap();
            // 发送成功
            if(isset($sendMessage['body']['Code']) && $sendMessage['body']['Code'] == 'OK'){
                return true;
            }
            // 获取错误信息
            $errMessage = isset($sendMessage['body']['Message']) ? $sendMessage['body']['Message'] : json_encode($sendMessage);
            // 获取错误代码
            $errCode = isset($sendMessage['body']['Code']) ? $sendMessage['body']['Code'] : 'error';
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