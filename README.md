# fayho
fayho framework depend on swoft



# 关于Services命名空间解说
- 所有类，都是针对*RPC 提供者*和*RPC 调用者*的
- 子目录，按不同子系统进行划分。如现有的Store目录，它是Store这个子系统的。所有的接口都是由Store子系统提供
- 每个子目录下面，都必须包含README.md，里面至少提供配置文件的内容



# 系统启动扫描
由于部分bean是写在这个库里面的，因此在系统的config/properties/app.php里面，需要将其加入到扫描目录  
```php
    'beanScan'     => [
        //其它扫描目录
        'Fayho\Middlewares' => BASE_PATH . '/vendor/fayho/fayho/src/Fayho/Middlewares',
        'Fayho\Services' => BASE_PATH . '/vendor/fayho/fayho/src/Fayho/Services',
    ],
```
或者是本系统去继承相对应的 Fayho 下面的类，放到 app 对应的目录里面  