<?php
namespace xutongle\apistore\clients;

use xutongle\apistore\Baidu;

class Mobile extends Baidu
{

    /**
     * get mobile
     *
     * @param string mobile number
     * @return array
     */
    public function get($mobile)
    {
        $response = $this->api('apistore/mobilenumber/mobilenumber', 'GET', ['phone' => $mobile]);
        return $response;
    }

    /**
     * @inheritdoc
     */
    protected function defaultName()
    {
        return 'mobile';
    }

}