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

return [
    // 默认短信平台
    'default' => 'qiniu',
    // 短信平台配置
    'platforms' => [
        // 七牛云
        'qiniu' => [
            // 公钥
            'access_key' => '',
            // 私钥
            'secret_key' => '',
            // 模板ID
            'template_id' => '',
        ],
        // 腾讯云
        'tencent' => [
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
        ],
        // 阿里云
        'aliyun' => [
            // 公钥
            'access_id' => '',
            // 私钥
            'access_secret' => '',
            // 模板ID
            'template_id' => '',
            // 已审核的签名
            'sign_name' => '',
            // 服务接入点, 默认dysmsapi.aliyuncs.com
            'endpoint' => '',
        ],
        // 百度云
        'baidu' => [
            // 公钥
            'access_key' => '',
            // 私钥
            'secret_key' => '',
            // 模板ID
            'template_id' => '',
            // 签名ID
            'signature_id' => '',
            // 服务接入点, 默认smsv3.bj.baidubce.com
            'endpoint' => '',
        ]
    ],
];
