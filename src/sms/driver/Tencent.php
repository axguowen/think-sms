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

class Tencent extends Platform
{
	/**
     * 平台句柄
     * @var \TencentCloud\Sms\V20210111\SmsClient
     */
    protected $handler;

	/**
     * 平台配置参数
     * @var array
     */
    protected $options = [
        // 公钥
        'secret_id' => '',
        // 私钥
        'secret_key' => '',
		// 短信应用ID
		'sdk_app_id' => '',
        // 模板ID
        'template_id' => '',
		// 已审核的签名
		'sign_name' => '',
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

        // 实例化一个认证对象，入参需要传入腾讯云账户密钥对secretId，secretKey。
		$cred = new \TencentCloud\Common\Credential($this->options['secret_id'], $this->options['secret_key']);
		// 实例化要请求产品的client对象
		// 第二个参数是地域信息，可以直接填写字符串ap-guangzhou，支持的地域列表参考 https://cloud.tencent.com/document/api/382/52071#.E5.9C.B0.E5.9F.9F.E5.88.97.E8.A1.A8
		$this->handler = new \TencentCloud\Sms\V20210111\SmsClient($cred, 'ap-guangzhou');
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
        // 处理参数
        $params = [];
        // 遍历
        foreach($data as $k => $v){
            $params[] = $v;
        }

        // 获取模板
        $template = is_null($template) ? $this->options['template_id'] : $template;

		// 实例化一个 sms 发送短信请求对象,每个接口都会对应一个request对象。
		$req = new \TencentCloud\Sms\V20210111\Models\SendSmsRequest();

		// 填充请求参数,这里request对象的成员变量即对应接口的入参
		// 短信应用ID: 短信SdkAppId在 [短信控制台] 添加应用后生成的实际SdkAppId，示例如1400006666
		// 应用 ID 可前往 [短信控制台](https://console.cloud.tencent.com/smsv2/app-manage) 查看
		$req->SmsSdkAppId = $this->options['sdk_app_id'];
		/* 短信签名内容: 使用 UTF-8 编码，必须填写已审核通过的签名 */
		$req->SignName = $this->options['sign_name'];
		/* 模板 ID: 必须填写已审核通过的模板 ID */
		$req->TemplateId = $template;
		/* 模板参数: 模板参数的个数需要与 TemplateId 对应模板的变量个数保持一致，若无模板参数，则设置为空*/
		$req->TemplateParamSet = $params;
		/* 下发手机号码，采用 E.164 标准，+[国家或地区码][手机号]
		* 示例如：+8613711112222， 其中前面有一个+号 ，86为国家码，13711112222为手机号，最多不要超过200个手机号*/
		$req->PhoneNumberSet = $this->mobiles;
		// 通过client对象调用SendSms方法发起请求。注意请求方法名与请求对象是对应的
		// 返回的response是一个SendSmsResponse类的实例，与请求对象对应
        try{
            $sendMessage = $this->handler->SendSms($req)->toJsonString();
            $sendMessage = json_decode($sendMessage, true);
            // 发送成功
            if(isset($sendMessage['SendStatusSet'][0]['Code']) && $sendMessage['SendStatusSet'][0]['Code'] == 'Ok'){
                return true;
            }
            // 获取错误信息
            $errMessage = isset($sendMessage['Error']['Message']) ? $sendMessage['Error']['Message'] : json_encode($sendMessage);
            // 获取错误代码
            $errCode = isset($sendMessage['Error']['Code']) ? $sendMessage['Error']['Code'] : 'error';
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