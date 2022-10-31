# think-sms

ThinkPHP6.0 Think-Sms短信扩展


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
具体的配置请参考think-sms库。