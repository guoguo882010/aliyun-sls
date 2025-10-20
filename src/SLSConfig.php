<?php

namespace RSHDSDK\ALiYunSLS;

use InvalidArgumentException;

class SLSConfig
{
    /**
     * @var array
     */
    protected $config;

    public function __construct($config)
    {
        if (empty($config)) {
            throw new InvalidArgumentException('缺少配置文件');
        }

        $endpoint = $config['endpoint'] ?? '';
        $accessKeyId = $config['accessKeyId'] ?? '';
        $accessKeySecret = $config['accessKeySecret'] ?? '';
        $project = $config['project'] ?? '';
        $logStore = $config['logStore'] ?? '';

        if (empty($endpoint)) {
            throw new InvalidArgumentException('服务接入点 endpoint 不能为空');
        }

        if (empty($accessKeyId)) {
            throw new InvalidArgumentException('accessKeyId 不能为空');
        }

        if (empty($accessKeySecret)) {
            throw new InvalidArgumentException('accessKeySecret 不能为空');
        }

        if (empty($project)) {
            throw new InvalidArgumentException('项目名称 project 不能为空');
        }

        if (empty($logStore)) {
            throw new InvalidArgumentException('存储仓库名称 logStore 不能为空');
        }

        $this->config = $config;
    }

    public function getEndpoint()
    {
        return $this->config['endpoint'] ?? '';
    }

    public function getAccessKeyId()
    {
        return $this->config['accessKeyId'] ?? '';
    }

    public function getAccessKeySecret()
    {
        return $this->config['accessKeySecret'] ?? '';
    }

    public function getProject()
    {
        return $this->config['project'] ?? '';
    }

    /**
     * 存储仓库名称
     * @return string
     */
    public function getLogStore()
    {
        return $this->config['logStore'] ?? '';
    }

    /**
     * 是否关闭写入日志 true关闭 false 不关闭
     * @return bool
     */
    public function getClose()
    {
        if ($this->config['close'] === false) {
            return false;
        }
        return true;
    }
}