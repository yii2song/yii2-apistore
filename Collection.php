<?php

namespace xutongle\apistore;

use Yii;
use yii\base\Component;
use yii\base\InvalidParamException;


/**
 * Collection is a storage for all api clients in the application.
 *
 * Example application configuration:
 *
 * ~~~
 * 'services' => [
 *     'authClientCollection' => [
 *         'class' => \xutongle\apistore\Collection::className(),
 *         'apis' => [
 *             'google' => [
 *                 'class' => 'xutongle\apistore\clients\Mobile',
 *                 'apiKey'=>'123456'
 *             ],
 *         ],
 *     ]
 *     ...
 * ]
 * ~~~
 *
 * @property ClientInterface[] $apis List of api clients. This property is read-only.
 *
 * @author Xu Tongle <xutongle@gmail.com>
 * @since 3.0
 */
class Collection extends Component
{
  /**
     * @var array list of Api clients with their configuration in format: 'clientId' => [...]
     */
    private $_apis = [];

    /**
     * @param array $clients list of api clients
     */
    public function setApis(array $apis)
    {
        $this->_apis = $apis;
    }

    /**
     * @return ClientInterface[] list of api clients.
     */
    public function getApis()
    {
        $apis = [];
        foreach ($this->_apis as $id => $api) {
            $apis[$id] = $this->getApi($id);
        }
        return $apis;
    }

    /**
     * @param string $id service id.
     * @return ClientInterface api client instance.
     * @throws InvalidParamException on non existing client request.
     */
    public function getApi($id)
    {
        if (!array_key_exists($id, $this->_apis)) {
            throw new InvalidParamException("Unknown api client '{$id}'.");
        }
        if (!is_object($this->_apis[$id])) {
            $this->_apis[$id] = $this->createApi($id, $this->_apis[$id]);
        }
        return $this->_apis[$id];
    }

    /**
     * Checks if client exists in the hub.
     * @param string $id client id.
     * @return boolean whether client exist.
     */
    public function hasApi($id)
    {
        return array_key_exists($id, $this->_apis);
    }

    /**
     * Creates auth client instance from its array configuration.
     * @param string $id api client id.
     * @param array $config api client instance configuration.
     * @return ClientInterface api client instance.
     */
    protected function createApi($id, $config)
    {
        $config['id'] = $id;
        return Yii::createObject($config);
    }
}
