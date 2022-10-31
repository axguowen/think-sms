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

class Qiniu extends Platform
{
    /**
     * 平台句柄
     * @var \Qiniu\Sms\Sms
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
		$auth = new \Qiniu\Auth($this->options['access_key'], $this->options['secret_key']);
		// 实例化短信模型
		$this->handler = new \Qiniu\Sms\Sms($auth);
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
            $sendMessage = $this->handler->sendMessage($template, $this->mobiles, $data);
            // 发送成功
            if(isset($sendMessage[0]['job_id']) && $sendMessage[0]['job_id']>0){
                return true;
            }
            // 获取错误信息
            $errMessage = isset($sendMessage['message']) ? $sendMessage['message'] : json_encode($sendMessage);
            // 获取错误代码
            $errCode = isset($sendMessage['error']) ? $sendMessage['error'] : 'error';
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