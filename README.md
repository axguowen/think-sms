# think-sms

ThinkPHP6.0 短信扩展

主要功能：
支持多平台短信配置：目前支持阿里云、百度云、七牛云、腾讯云平台；
可扩展自定义平台驱动；
支持facade门面方式调用；
支持动态指定短信模板；
支持指定多个手机号接收；
支持动态切换平台；

## 安装

~~~php
composer require axguowen/think-sms
~~~

## 用法示例

本扩展不能单独使用，依赖ThinkPHP6.0+

首先配置config目录下的sms.php配置文件，然后可以按照下面的用法使用。

简单使用

~~~php

use axguowen\facade\Sms;

// 发送短信
Sms::setMobiles('188****8888')->send(['code' => '486936']);

~~~

修改默认模板

~~~php

use axguowen\facade\Sms;

// 修改默认模板
Sms::setMobiles('188****8888')
    ->setTemplate('10052***1129')
    ->send(['code' => '486936']);

~~~

发送时传入临时指定的模板ID

~~~php

use axguowen\facade\Sms;

// 发送短信send方法第二个参数直接传模板ID
Sms::setMobiles('188****8888')
    ->send(['code' => '486936'], '10052***1129');

~~~

指定多个手机号

~~~php

use axguowen\facade\Sms;

// 同时发送给多个手机号
Sms::setMobiles('188****8888,155****5050')
    ->send(['msg' => 'Thank you!']);

// 支持数组传入
Sms::setMobiles(['188****8888', '155****5050'])
    ->send(['msg' => 'Thank you!']);

~~~

动态切换平台

~~~php

use axguowen\facade\Sms;

// 使用腾讯云短信平台
Sms::platform('tencent')
    ->setMobiles('188****8888')
    ->send(['code' => '486936']);

~~~

## 配置说明

~~~php

// 短信配置
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

~~~