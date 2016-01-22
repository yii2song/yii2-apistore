创建你自己的Api客户端
======================

你可以为任意的外部Api服务商创建你自己的Api客户端。
以下为你的扩展提供的基类的名称：

[[xutongle\apistore\BaseClient]].

在此步骤中，你可以决定验证客户端的默认名称和标题选项，声明相应的方法：

```php
use xutongle\apistore\BaseClient;

class MyApiClient extends BaseClient
{
    protected function defaultName()
    {
        return 'my_auth_client';
    }
}
```

根据实际使用的基类，你需要重新声明不同的域和方法。

