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

namespace axguowen\sms;

abstract class Platform
{
	/**
     * 平台句柄
     * @var object
     */
    protected $handler = null;

	/**
     * 平台配置参数
     * @var array
     */
	protected $options = [];

	/**
     * 接收短信的手机号集合
     * @var array
     */
	protected $mobiles = [];

	/**
     * 设置手机号
     * @access public
     * @param  array $mobiles 手机号集合
     * @return $this
     */
    public function setMobiles($mobiles)
    {
		// 如果不是数组
		if(!is_array($mobiles)){
			$mobiles = explode(',', $mobiles);
		}
        // 赋值
        $this->mobiles = $mobiles;
        // 返回
        return $this;
    }

	/**
     * 追加手机号
     * @access public
     * @param  array $mobiles 手机号集合
     * @return $this
     */
    public function appendMobiles($mobiles)
    {
		// 如果不是数组
		if(!is_array($mobiles)){
			$mobiles = explode(',', $mobiles);
		}
        // 合并手机号集合
        $mobiles = array_merge($this->mobiles, $mobiles);
        // 返回
        return $this->setMobiles($mobiles);
    }

	/**
     * 设置模板
     * @access public
     * @param  string $template 短信模板
     * @return $this
     */
    public function setTemplate($template)
    {
        $this->options['template_id'] = $template;
        // 返回
        return $this;
    }

	/**
     * 返回平台句柄对象，可执行其它高级方法
     * @access public
     * @return object
     */
    public function handler()
    {
        return $this->handler;
    }

	public function __call($method, $args)
    {
        return call_user_func_array([$this->handler, $method], $args);
    }
}