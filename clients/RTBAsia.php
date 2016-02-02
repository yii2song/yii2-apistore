<?php
namespace xutongle\apistore\clients;

use xutongle\apistore\Baidu;

class RTBAsia extends Baidu
{
    /**
     * get ip
     *
     * @param string ip
     * @return array
     */
    public function get($ip)
    {
        $response = $this->api('rtbasia/non_human_traffic_screening_vp/nht_query', 'GET', ['ip' => $ip]);
        return $response;
    }

    /**
     * get ip
     *
     * @param string ip
     * @return array
     */
    public function free($ip){
        $response = $this->api('rtbasia/non_human_traffic_screening/nht_query', 'GET', ['ip' => $ip]);
        return $response;
    }

    /**
     * 获取IP地址的经纬度
     * @param $ip
     * @return array
     */
    public function getIpLocation($ip){
        $response = $this->api('rtbasia/ip_location/ip_location', 'GET', ['ip' => $ip,'v'=>'1.1']);
        return $response;
    }

    /**
     * 获取IP地址的类型
     * @param $ip
     * @return array
     */
    public function getIpType($ip){
        $response = $this->api('rtbasia/ip_type/ip_type', 'GET', ['ip' => $ip]);
        return $response;
    }

    /**
     * @inheritdoc
     */
    protected function defaultName()
    {
        return 'RTBAsia';
    }
}
