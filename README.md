# 阿里云 SLS 日志

**安装**

```shell
composer require guoguo882010/aliyun-sls
```

**使用**

```php
$config = [
    //服务接入点 https://help.aliyun.com/zh/sls/developer-reference/api-sls-2020-12-30-endpoint?spm=a2c4g.11186623.help-menu-28958.d_9_2_1.a0557b7czYrsET&scm=20140722.H_2771272._.OR_help-T_cn~zh-V_1
    
    'endpoint' => 'http://cn-chengdu-intranet.log.aliyuncs.com',

    //秘钥 id
    'accessKeyId' => 'key id',

    //秘钥 密码
    'accessKeySecret' => 'key secret',

    //项目名称
    'project'         => 'log-service',

    //存储仓库名称
    'logStore'        => 'store',

    //是否关闭写入日志
    'close'           => false
];

$sls = new \RSHDSDK\ALiYunSLS\ALiYunSLS($config);

//需要记录的日志
$data = [
    'name' => '名字',
    'age' => '12',
    //数组会转换为 json 格式存入 sls
    'address' => [
        '地址一' => '北京市',
        '地址二' => '成都市',
    ],
];

//主题，可以理解为分类，对日志进行分类
$topic = '主题';

//写入日志
$sls->putDataLog($data,$topic);

//查询日志
$sls->getLogs('主题','开始时间戳','结束时间戳','sls查询语句','每页显示数，最大100','第几页','根据日志时间、正序排序 false 倒序排序 true 默认 true')

```