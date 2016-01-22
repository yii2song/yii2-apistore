<?php
/**
 * Leaps Framework [ WE CAN DO IT JUST THINK IT ]
 *
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2015 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace xutongle\apistore\clients;

use xutongle\apistore\BaseClient;

class Ip extends BaseClient
{
    /**
     * @var string API base URL.
     */
    public $apiBaseUrl = 'http://ip.taobao.com/';

    /**
     * get ip
     *
     * @param array ip
     * @return array
     */
    public function get($ip)
    {
        $response = $this->api('/service/getIpInfo.php', 'GET', ['ip' => $ip]);
        return $response;
    }

    /**
     * @inheritdoc
     */
    protected function defaultName()
    {
        return 'ip';
    }
}
