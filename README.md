用于 YII 的 ApiStore 扩展
==============================

该扩展为 Yii framework 添加 [ApiStore](http://apistore.baidu.com/) 客户端。

For license information check the [LICENSE](LICENSE.md)-file.

Documentation is at [docs/guide/README.md](docs/guide/README.md).

[![Latest Stable Version](https://poser.pugx.org/xutongle/yii2-apistore/v/stable.png)](https://packagist.org/packages/xutongle/yii2-apistore)
[![Total Downloads](https://poser.pugx.org/xutongle/yii2-apistore/downloads.png)](https://packagist.org/packages/xutongle/yii2-apistore)
[![Build Status](https://travis-ci.org/xutongle/yii2-apistore.svg?branch=master)](https://travis-ci.org/xutongle/yii2-apistore)

安装
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
composer require --prefer-dist xutongle/yii2-apistore
```

or add

```json
"xutongle/yii2-apistore": ">=1.0.0"
```

to the `require` section of your composer.json.

注意:本扩展的二次开发非常简单,我只做了百度的手机归属地查询和身份证查询,这两个就一个方法,只要继承Baidu类并实现get方法即可无限扩展百度的API,
当然也有建大的办法,即直接使用百度类作为api类,获取到实例后如下即可:
````
$response = $baidu->api('apistore/mobilenumber/mobilenumber', 'GET', ['phone' => $mobile]);
return $response;
````
