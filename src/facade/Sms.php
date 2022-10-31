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

namespace axguowen\facade;

use think\Facade;

/**
 * Class Sms
 * @package axguowen\facade
 * @mixin \axguowen\Sms
 * @method static Platform platform(string $name = null) ,null|string
 * @method static mixed getConfig(null|string $name = null, mixed $default = null) 获取短信配置
 * @method static array getPlatformConfig(string $platform, null $name = null, null $default = null) 获取平台配置
 * @method static string|null getDefaultDriver() 默认平台
 * @method static mixed send(array $data, null|string $template = null) 发送短信
 */
class Sms extends Facade
{
    /**
     * 获取当前Facade对应类名
     * @access protected
     * @return string
     */
    protected static function getFacadeClass()
    {
        return \axguowen\Sms::class;
    }
}