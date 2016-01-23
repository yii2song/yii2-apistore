<?php

namespace xutongle\apistore\clients;

use xutongle\apistore\Baidu;

class Translate extends Baidu
{

    /**
     * 翻译
     *
     * @param string $query 待翻译内容,utf-8，urlencode编码
     * @param string $from 源语言语种：语言代码或auto
     * @param string $to 目标语言语种：语言代码或auto
     * @return array
     */
    public function get($query, $from = 'auto', $to = 'auto')
    {
        $query = urlencode($query);
        $response = $this->api('apistore/tranlateservice/translate', 'GET', ['query' => $query, $from, $to]);
        return $response;
    }

    /**
     * @inheritdoc
     */
    protected function defaultName()
    {
        return 'translate';
    }

}
