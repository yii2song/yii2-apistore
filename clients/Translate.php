<?php

namespace xutongle\apistore\clients;

use xutongle\apistore\Baidu;
use xutongle\apistore\BaseClient;

/**
 * Class Translate 实现了百度翻译的相关接口
 * @package xutongle\apistore\clients
 */
class Translate extends BaseClient
{

    /**
     * @var
     */
    public $appId;

    public $appKey;

    /**
     * 指定百度翻译的API网址
     */
    public $apiBaseUrl = "http://api.fanyi.baidu.com/";

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (empty ($this->appId)) {
            throw new InvalidConfigException ('The "appId" property must be set.');
        }
        if (empty ($this->appKey)) {
            throw new InvalidConfigException ('The "appKey" property must be set.');
        }
    }

    /**
     * 翻译
     *
     * @param string $query 待翻译内容,utf-8，urlencode编码
     * @param string $from 源语言语种：语言代码或auto
     * @param string $to 目标语言语种：语言代码或auto
     * @return array
     */
    public function get($query, $from = 'auto', $to = 'zh')
    {
        $response = $this->api('api/trans/vip/translate', 'POST', ['q' => $query, 'from' => $from, 'to' => $to]);
        return $response;
    }

    /**
     * @inheritdoc
     */
    protected function apiInternal($url, $method, array $params, array $headers)
    {
        $salt = uniqid();
        $sign = md5($this->appId . $params['q'] . $salt . $this->appKey);
        $params = array_merge($params, ['appid' => $this->appId, 'sign' => $sign, 'salt' => $salt]);
        return parent::apiInternal($url, $method, $params, $headers);
    }

    /**
     * @inheritdoc
     */
    protected function defaultName()
    {
        return 'translate';
    }

}
