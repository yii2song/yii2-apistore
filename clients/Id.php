<?php

namespace xutongle\apistore\clients;

use xutongle\apistore\Baidu;

class Id extends Baidu
{

    /**
     * get id
     *
     * @param array $id ID Number
     * @return array
     */
    public function get($id)
    {
        $response = $this->api('apistore/idservice/id', 'GET', ['id' => $id]);
        return $response;
    }

    /**
     * @inheritdoc
     */
    protected function defaultName()
    {
        return 'id';
    }

}