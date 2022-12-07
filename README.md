通过观察者模式开发的webman框架简易版事件扩展包；
通常用于用户注册后的一系列操作，可在不更改原有代码的同时添加额外的扩展功能，降低耦合性。

此事件额外还开发了生成事件的命令行功能。

## 安装
```shell script
composer require qiyunhai/webman-event
```

### 配置
事件默认配置文件 `config/plugin/qiyunhai/event/event.php`
```php
return [
    // 此处可替换为 app\events\Test::class
    'app\events\Test' => [
        'app\listeners\test\ObServer1',
        'app\listeners\test\ObServer2',
    ],
];
```
`注意：配置文件也可以放在其他地方，只需更改触发事件的event方法即可`

命令行生成事件 `-l` 检查配置文件里不存在的事件进行创建操作
`-c` 事件的配置文件，下面示例的配置文件为`config/event.php`

```shell script
php webman qiyunhai:event -c event -l
```

### 快速开始

事件类 `app\events\Test`
```php
namespace app\events;

class Test
{
    public $id;

    public function __construct($id = null)
    {
        $this->id = $id;
    }
}
```

监听类1 `app\listeners\test\ObServer1`
```php
namespace app\listeners\test;

use app\events\Test;

class ObServer1
{
    public function run(Test $event)
    {
        $res = 'ObServer 1, id:'.$event->id;
        $event->id = 2;

        return $res;
    }
}
```

监听类2 `app\listeners\test\ObServer2`
```php
namespace app\listeners\test;

use app\events\Test;

class ObServer2
{
    public function run(Test $event)
    {
        return 'ObServer 2, id:'.$event->id;
    }
}
```

### 触发事件
```php
event(new (app\events\Test(1)));
```
### 执行结果
```
array(
    [0] => "ObServer 1, id:1",
    [1] => "ObServer 2, id:2"
)
```