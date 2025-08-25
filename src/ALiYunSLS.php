<?php

namespace RSHDSDK\ALiYunSLS;

use Aliyun_Log_Client;
use Aliyun_Log_Exception;
use Aliyun_Log_Models_GetLogsRequest;
use Aliyun_Log_Models_GetLogsResponse;
use Aliyun_Log_Models_LogItem;
use Aliyun_Log_Models_PutLogsRequest;
use InvalidArgumentException;

class ALiYunSLS
{
    /**
     * @var Aliyun_Log_Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $project;

    /**
     * @var string
     */
    protected $logStore;

    /**
     * 是否关闭写入日志
     * @var bool
     */
    protected $close;

    /**
     * @param array $config
     * @throws Aliyun_Log_Exception
     * @throws InvalidArgumentException
     */
    public function __construct($config)
    {
        $c = new SLSConfig($config);

        $this->client = new Aliyun_Log_Client($c->getEndpoint(), $c->getAccessKeyId(), $c->getAccessKeySecret());

        $this->project = $c->getProject();

        $this->logStore = $c->getLogStore();

        $this->close = $c->getClose();
    }

    /**
     * @param array $data
     * @param string $topic
     * @return void
     * @throws Aliyun_Log_Exception
     */
    public function putDataLog($data, $topic = '空')
    {
        $param = $this->arrayValueToJson($data);

        $this->putLog($param, $topic);
    }

    /**
     * 写日志到阿里 sls
     * @param array $contents
     * @param string $topic
     * @return void
     * @throws Aliyun_Log_Exception
     */
    protected function putLog($contents, $topic)
    {
        if ($this->close) {
            return;
        }

        $logItem = new Aliyun_Log_Models_LogItem();
        $logItem->setTime(time());
        $logItem->setContents($contents);
        $logitems = array($logItem);
        $request = new Aliyun_Log_Models_PutLogsRequest($this->project, $this->logStore, $topic, null, $logitems);

        $this->client->putLogs($request);
    }

    /**
     * 数组转为json字符串
     * @param $array
     * @return array
     */
    protected function arrayValueToJson($array)
    {
        //数组转为json字符串
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                //JSON_UNESCAPED_UNICODE 不编码中文
                //JSON_UNESCAPED_SLASHES 不转义字符 比如在 / 前面在 \/
                $array[$k] = json_encode($v, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            }
        }

        return $array;
    }

    /**
     * 获取日志
     * @param string $topic 日志主题
     * @param int $stratTime 日志查询开始时间，时间戳类型
     * @param int $endTime 日志查询结束时间，时间戳类型
     * @param string $query sls日志查询语句，例如 'age: 12 and name.id:229774'
     * @param int $pageSize 每页显示数量，最大100
     * @param int $offset 分页参数，从第几条记录开始显示
     * @param bool $reverse 根据日志时间、正序排序 false 倒序排序 true
     * @return Aliyun_Log_Models_GetLogsResponse
     * @throws Aliyun_Log_Exception
     */
    public function getLogs($topic, $stratTime, $endTime, $query, $pageSize = 100 ,$offset = 0, $reverse = true)
    {
        $request = new Aliyun_Log_Models_GetLogsRequest(
            $this->project,
            $this->logStore, $stratTime, $endTime, $topic, $query, $pageSize, $offset, $reverse);

        return  $this->client->getLogs($request);
    }
}