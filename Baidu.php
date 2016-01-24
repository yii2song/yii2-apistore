<?php
namespace xutongle\apistore;

class Baidu extends BaseClient
{
    /**
     * @var string API base URL.
     */
    public $apiBaseUrl = 'http://apis.baidu.com/';

    /**
     * @var string 百度ApiKey
     */
    public $apiKey;

    /**
     * @inheritdoc
     */
    protected function apiInternal($url, $method, array $params, array $headers)
    {
        $headers = array_merge($headers, ['apikey:'.$this->apiKey]);
        return parent::apiInternal($url, $method, $params, $headers);
    }
}
