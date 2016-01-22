快速开始
========

````
        $mobile = Yii::$app->apiClientCollection->getApi('mobile');
        $b = $mobile->get('13800138000');
        print_r($b);
        exit;
````