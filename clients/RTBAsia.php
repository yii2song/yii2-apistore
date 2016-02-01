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
     * @inheritdoc
     */
    protected function defaultName()
    {
        return 'RTBAsia';
    }
}
