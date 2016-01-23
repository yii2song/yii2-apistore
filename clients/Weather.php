<?php
namespace xutongle\apistore\clients;

use xutongle\apistore\Baidu;

class Weather extends Baidu
{

    /**
     * 天气查询,根据城市拼音
     * @param $cityPinyin 城市拼音
     * @return array
     */
    public function getWeatherByPinyin($cityPinyin){
        $response = $this->api('apistore/weatherservice/weather', 'GET', ['citypinyin' => $cityPinyin]);
        return $response;
    }

    /**
     * 天气查询,根据城市名称
     * @param $cityName 城市名称
     * @return array
     */
    public function getWeatherByCityName($cityName){
        $response = $this->api('apistore/weatherservice/cityname', 'GET', ['cityname' => $cityName]);
        return $response;
    }

    /**
     * 天气查询,根据城市ID
     * @param $cityID 城市ID
     * @return array
     */
    public function getWeatherByCityID($cityID){
        $response = $this->api('apistore/weatherservice/cityid', 'GET', ['cityid' => $cityID]);
        return $response;
    }

    /**
     * 查询可用城市列表
     *
     * @param string $cityName 城市中文名称
     * @return array
     */
    public function getCityList($cityName)
    {
        $response = $this->api('apistore/weatherservice/citylist', 'GET', ['cityname' => $cityName]);
        return $response;
    }

    /**
     * 查询城市信息列表
     *
     * @param string $cityName 城市中文名称
     * @return array
     */
    public function getCityInfo($cityName)
    {
        $response = $this->api('apistore/weatherservice/cityinfo', 'GET', ['cityname' => $cityName]);
        return $response;
    }

    /**
     * 天气查询_带历史7天和未来四天
     *
     * @param string $cityName 城市中文名称
     * @return array
     */
    public function getRecentWeather($cityName,$cityId)
    {
        $response = $this->api('apistore/weatherservice/recentweathers', 'GET', ['cityname' => $cityName,'cityid'=>$cityId]);
        return $response;
    }

    /**
     * @inheritdoc
     */
    protected function defaultName()
    {
        return 'weather';
    }

}
