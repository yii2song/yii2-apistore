安装
====

## 安装扩展

要安装该扩展，请使用 Composer。运行
                                            
```
composer require --prefer-dist xutongle/yii2-apistore "*"
```

或在你的 composer.json 文件的“require”一节添加以下代码：

```json
"xutongle/yii2-apistore": "*"
```

## 配置应用程序

该扩展安装后，你需要设置验证客户端集合应用程序组件：

```php
'services' => [
    'apiClientCollection' => [
        'class' => 'xutongle\apistore\Collection',
        'apis' => [
            'mobile' => [
                'class' => 'xutongle\apistore\clients\Mobile',
                'apiKey'=>'aaaaa',
            ],
            'id' => [
                'class' => 'xutongle\apistore\clients\Id',
                'apiKey'=>'aaaaa',
            ],
            // etc.
        ],
    ]
    ...
]
```

提供了以下几个立即可用的客户端：

- [[xutongle\apistore\clients\Mobile|Mobile]].
- [[xutongle\apistore\clients\Id|ID]].

配置每个客户端稍有不同。

