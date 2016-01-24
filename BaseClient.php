<?php

namespace xutongle\apistore;

use Yii;
use yii\helpers\Json;
use yii\base\Component;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/**
 * BaseClient is a base Api Client class.
 *
 * @see ClientInterface
 *
 * @property string $id Service id.
 * @property string $name Service name.
 * @property array $normalizeApiAttributeMap Normalize api attribute map.
 * @property string $title Service title.
 * @property array $apiAttributes List of api attributes.
 *
 * @author Xu Tongle <xutongle@gmail.com>
 * @since 2.0
 */
abstract class BaseClient extends Component implements ClientInterface
{

    const CONTENT_TYPE_JSON = 'json';
    // JSON format

    const CONTENT_TYPE_URLENCODED = 'urlencoded';
    // urlencoded query string, like name1=value1&name2=value2

    const CONTENT_TYPE_XML = 'xml';
    // XML format

    const CONTENT_TYPE_AUTO = 'auto';
    // attempts to determine format automatically


    /**
     * @var string api service id.
     * This value mainly used as HTTP request parameter.
     */
    private $_id;

    /**
     * @var string api service name.
     * This value may be used in database records, CSS files and so on.
     */
    private $_name;

    /**
     * @var array cURL request options. Option values from this field will overwrite corresponding
     * values from [[defaultCurlOptions()]].
     */
    private $_curlOptions = [];

    /**
     * @param string $id service id.
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * @return string service id
     */
    public function getId()
    {
        if (empty($this->_id)) {
            $this->_id = $this->getName();
        }
        return $this->_id;
    }

    /**
     * @param string $name service name.
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * @return string service name.
     */
    public function getName()
    {
        if ($this->_name === null) {
            $this->_name = $this->defaultName();
        }
        return $this->_name;
    }

    /**
     * @param array $curlOptions cURL 参数
     */
    public function setCurlOptions(array $curlOptions)
    {
        $this->_curlOptions = $curlOptions;
    }

    /**
     * @return array cURL 参数
     */
    public function getCurlOptions()
    {
        return $this->_curlOptions;
    }

    /**
     * Performs request to the API.
     * @param string $apiSubUrl API sub URL, which will be append to [[apiBaseUrl]], or absolute API URL.
     * @param string $method request method.
     * @param array $params request parameters.
     * @param array $headers additional request headers.
     * @return array API response
     * @throws Exception on failure.
     */
    public function api($apiSubUrl, $method = 'GET', array $params = [], array $headers = [])
    {
        if (preg_match('/^https?:\\/\\//is', $apiSubUrl)) {
            $url = $apiSubUrl;
        } else {
            $url = $this->apiBaseUrl . '/' . $apiSubUrl;
        }
        return $this->apiInternal($url, $method, $params, $headers);
    }

    /**
     * Sends HTTP request.
     * @param string $method request type.
     * @param string $url request URL.
     * @param array $params request params.
     * @param array $headers additional request headers.
     * @return array response.
     * @throws Exception on failure.
     */
    protected function sendRequest($method, $url, array $params = [], array $headers = [])
    {
        $curlOptions = $this->mergeCurlOptions(
            $this->defaultCurlOptions(),
            $this->getCurlOptions(),
            [
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_URL => $url
           ],
            $this->composeRequestCurlOptions(strtoupper($method), $url, $params)
        );
        $curlResource = curl_init();
        foreach ($curlOptions as $option => $value) {
            curl_setopt($curlResource, $option, $value);
        }
        $response = curl_exec($curlResource);
        $responseHeaders = curl_getinfo($curlResource);

        // check cURL error
        $errorNumber = curl_errno($curlResource);
        $errorMessage = curl_error($curlResource);

        curl_close($curlResource);

        if ($errorNumber > 0) {
            throw new Exception('Curl error requesting "' . $url . '": #' . $errorNumber . ' - ' . $errorMessage);
        }
        if (strncmp($responseHeaders['http_code'], '20', 2) !== 0) {
            throw new InvalidResponseException($responseHeaders, $response, 'Request failed with code: ' . $responseHeaders['http_code'] . ', message: ' . $response);
        }
        return $this->processResponse($response, $this->determineContentTypeByHeaders($responseHeaders));
    }

    /**
     * 合并基础URL和参数
     * @param string $url base URL.
     * @param array $params GET params.
     * @return string composed URL.
     */
    protected function composeUrl($url, array $params = [])
    {
        if (strpos($url, '?') === false) {
            $url .= '?';
        } else {
            $url .= '&';
        }
        $url .= http_build_query($params, '', '&', PHP_QUERY_RFC3986);
        return $url;
    }

    /**
     * 合并Curl参数
     * If each options array has an element with the same key value, the latter
     * will overwrite the former.
     * @param array $options1 options to be merged to.
     * @param array $options2 options to be merged from. You can specify additional
     * arrays via third argument, fourth argument etc.
     * @return array merged options (the original options are not changed.)
     */
    protected function mergeCurlOptions($options1, $options2)
    {
        $args = func_get_args();
        $res = array_shift($args);
        while (! empty($args)) {
            $next = array_shift($args);
            foreach ($next as $k => $v) {
                if (is_array($v) && ! empty($res[$k]) && is_array($res[$k])) {
                    $res[$k] = array_merge($res[$k], $v);
                } else {
                    $res[$k] = $v;
                }
            }
        }
        return $res;
    }

    /**
     * 返回默认Curl参数
     * @return array cURL options.
     */
    protected function defaultCurlOptions()
    {
        return [CURLOPT_USERAGENT => Yii::$app->name . ' ApiStore Client',CURLOPT_CONNECTTIMEOUT => 30,CURLOPT_TIMEOUT => 30,CURLOPT_SSL_VERIFYPEER => false];
    }

    /**
     * Attempts to determine HTTP request content type by headers.
     * @param array $headers request headers.
     * @return string content type.
     */
    protected function determineContentTypeByHeaders(array $headers)
    {
        if (isset($headers['content_type'])) {
            if (stripos($headers['content_type'], 'json') !== false) {
                return self::CONTENT_TYPE_JSON;
            }
            if (stripos($headers['content_type'], 'urlencoded') !== false) {
                return self::CONTENT_TYPE_URLENCODED;
            }
            if (stripos($headers['content_type'], 'xml') !== false) {
                return self::CONTENT_TYPE_XML;
            }
        }
        return self::CONTENT_TYPE_AUTO;
    }

    /**
     * Processes raw response converting it to actual data.
     * @param string $rawResponse raw response.
     * @param string $contentType response content type.
     * @throws Exception on failure.
     * @return array actual response.
     */
    protected function processResponse($rawResponse, $contentType = self::CONTENT_TYPE_AUTO)
    {
        if (empty($rawResponse)) {
            return [];
        }
        switch ($contentType) {
            case self::CONTENT_TYPE_AUTO:
                {
                    $contentType = $this->determineContentTypeByRaw($rawResponse);
                    if ($contentType == self::CONTENT_TYPE_AUTO) {
                        throw new Exception('Unable to determine response content type automatically.');
                    }
                    $response = $this->processResponse($rawResponse, $contentType);
                    break;
                }
            case self::CONTENT_TYPE_JSON:
                {
                    $response = Json::decode($rawResponse, true);
                    break;
                }
            case self::CONTENT_TYPE_URLENCODED:
                {
                    $response = [];
                    parse_str($rawResponse, $response);
                    break;
                }
            case self::CONTENT_TYPE_XML:
                {
                    $response = $this->convertXmlToArray($rawResponse);
                    break;
                }
            default:
                {
                    throw new Exception('Unknown response type "' . $contentType . '".');
                }
        }
        return $response;
    }

    /**
     * 转换XML到数组
     * @param string|\SimpleXMLElement $xml xml to process.
     * @return array XML array representation.
     */
    protected function convertXmlToArray($xml)
    {
        if (! is_object($xml)) {
            $xml = simplexml_load_string($xml);
        }
        $result = (array) $xml;
        foreach ($result as $key => $value) {
            if (is_object($value)) {
                $result[$key] = $this->convertXmlToArray($value);
            }
        }
        return $result;
    }

    /**
     * Attempts to determine the content type from raw content.
     * @param string $rawContent raw response content.
     * @return string response type.
     */
    protected function determineContentTypeByRaw($rawContent)
    {
        if (preg_match('/^\\{.*\\}$/is', $rawContent)) {
            return self::CONTENT_TYPE_JSON;
        }
        if (preg_match('/^[^=|^&]+=[^=|^&]+(&[^=|^&]+=[^=|^&]+)*$/is', $rawContent)) {
            return self::CONTENT_TYPE_URLENCODED;
        }
        if (preg_match('/^<.*>$/is', $rawContent)) {
            return self::CONTENT_TYPE_XML;
        }
        return self::CONTENT_TYPE_AUTO;
    }

    /**
     * Generates service name.
     * @return string service name.
     */
    protected function defaultName()
    {
        return Inflector::camel2id(StringHelper::basename(get_class($this)));
    }

    /**
     * Composes HTTP request CUrl options, which will be merged with the default ones.
     * @param string $method request type.
     * @param string $url request URL.
     * @param array $params request params.
     * @return array CUrl options.
     * @throws Exception on failure.
     */
    protected function composeRequestCurlOptions($method, $url, array $params)
    {
        $curlOptions = [];
        switch ($method) {
            case 'GET': {
                $curlOptions[CURLOPT_URL] = $this->composeUrl($url, $params);
                break;
            }
            case 'POST': {
                $curlOptions[CURLOPT_POST] = true;
                $curlOptions[CURLOPT_HTTPHEADER] = ['Content-type: application/x-www-form-urlencoded'];
                $curlOptions[CURLOPT_POSTFIELDS] = http_build_query($params, '', '&', PHP_QUERY_RFC3986);
                break;
            }
            case 'HEAD': {
                $curlOptions[CURLOPT_CUSTOMREQUEST] = $method;
                if (!empty($params)) {
                    $curlOptions[CURLOPT_URL] = $this->composeUrl($url, $params);
                }
                break;
            }
            default: {
                $curlOptions[CURLOPT_CUSTOMREQUEST] = $method;
                if (!empty($params)) {
                    $curlOptions[CURLOPT_POSTFIELDS] = $params;
                }
            }
        }

        return $curlOptions;
    }

    /**
     * Performs request to the API.
     * @param OAuthToken $accessToken actual access token.
     * @param string $url absolute API URL.
     * @param string $method request method.
     * @param array $params request parameters.
     * @param array $headers additional request headers.
     * @return array API response.
     * @throws Exception on failure.
     */
    protected function apiInternal($url, $method, array $params, array $headers)
    {
        return $this->sendRequest($method, $url, $params, $headers);
    }
}
