

# think-smarty

基于tp6封装的smarty模板引擎。

## 文档
- [smarty-github仓库](https://github.com/smarty-php/smarty)
- [smarty文档](https://smarty-php.github.io/smarty/)
- [smarty中文文档](https://www.smarty.net/docs/zh_CN/)

## smarty的简介

- php模板引擎的鼻祖
- 一直有人在进行维护(支持php8.x)

## 为什么要封装think-smarty
虽然现在流行前后端分离，但是有时难免要用一下mvc这种开发方式做一些项目,但是thinkphp的模板引擎(ThinkTemplate)在
phpstorm中格式化html代码时会各种代码错乱和报错,开发起来特别闹心。

无图无真相

### ThinkTemplate
Tips:phpstorm中`Ctrl+Alt+L` 是格式化代码

![图片备注](https://img-blog.csdnimg.cn/c5d8e0b4318b422e9b7dda484d824727.gif)

### think-smarty
![图片备注](https://img-blog.csdnimg.cn/cd8fb7b896f043ff9b48a81c308b3586.gif)



## think-smarty的优点

- 会自动根据smarty官方的更新而下载最新的稳定版
- 简化手动集成的繁琐步骤、配置，开箱即用
- phpstorm官方内置支持smarty语法高亮，格式化，折叠
- 麻雀虽小，五脏俱全(没有阉割smarty的功能)

## 更新
- 20220421 调整编译和缓存文件目录到`runtime`目录,以保持干净的项目目录结构
- 20220418 调整assign()、display()方法名称为smarty_assign()、smarty_display(),避免和tp内置函数名冲突


# 安装

```
composer require ajiho/think-smarty
```

# 配置文件

/config/smarty.php

```php
<?php

return [
    // 开启缓存
    'caching' => false,
    // 缓存周期(开启缓存生效)
    'cache_lifetime' => 120,
    // 空格策略
    'auto_literal' => false,
    // 前缀(挂载全局中间件有效)
    'prefix'         => '',
    // 模板引擎左边标记
    'left_delimiter' => '<{',
    // 模板引擎右边标记
    'right_delimiter' => '}>',
];
```

# 使用

## 开启think-smarty
用法和tp6自带的`think\middleware\SessionInit`中间件一样，需要自己开启，且`api`应用通常也是不需要模板引擎的。
```
\ajiho\middleware\SmartyInit::class
```

中间件注册后系统会自动按照`smarty.php`配置的参数自动初始化`think-smarty`。


## phpstorm设置

然后根据配置文件`smarty.php`对`phpstorm`进行相应的设置,就可以舒适的开发啦

`ctrl+alt+s`，搜索`smarty`就可以打开如下设置面板


![图片备注](https://img-blog.csdnimg.cn/36d3d5617e65447c9d80a3a0fbe1a8d1.png)

注意:根据设置好后，要关闭项目重新打开phpstorm才会生效。

## 演示效果
### 基本使用演示
在`phpstorm`中对着视图文件右键，选择`复制路径/引用`->`来自内容根的路径`然后粘贴
到`smarty_display()`方法中即可

![图片备注](https://img-blog.csdnimg.cn/2921f4006d5a41119b21046c67d98226.gif)


### 开启调试效果

根据`.env`文件`APP_DEBUG = true`即可开启调试模式。

为什么不直接使用thinkphp的异常接管？因为在某些特殊的情况下你开启tp框架的DEBUG调试，它依然检查不出
错误,think-smarty使用自己的报错处理可以捕获任何错误。

![图片备注](https://img-blog.csdnimg.cn/aa57dcb049b949ed81e2f37cb34375e9.gif)

# 助手函数
| 函数名 | 描述 |
|--|--|
| smarty | 返回smarty对象,可以根据smarty官方文档调用一些方法 |
| smarty_assign | 给视图文件赋值 |
| smarty_fetch | 返回一个模板输出的内容(HTML代码)，而不是直接显示出来 |
| smarty_display | 返回一个response对象 |

# 配置说明

## 默认支持的修改的配置

| 参数 | 描述 |
|--|--|
| caching | 是否开启缓存 |
| cache_lifetime | 缓存周期 |
| auto_literal | 是否使用smarty3空格策略 |
| prefix | 配置、插件目录前缀(挂载到全局中间件时该参数才会生效) |
| left_delimiter | 模板引擎左边标记 |
| right_delimiter | 模板引擎右边标记 |


## think-smarty其它配置说明

| 参数 | 路径 | 说明 |
|--|--|--|
| 模板目录 | / | think-smarty的模板文件根路径默认是以项目根`/`目录作为起点的 |
| 配置目录 | /smarty/configs | 自定义smarty配置文件目录(一般很少用),如要使用需要手动新建 |
| 插件目录 | /smarty/plugins | 编写smarty插件的目录,(一般很少用),如要使用需要手动新建 |
| 编译目录 | /runtime/smarty/compile | 当调用`smarty_display`方法时会自动生成编译文件 |
| 缓存目录 | /runtime/smarty/cache | 开启缓存时会自动生成缓存文件 |

## 对编译,缓存,配置,插件目录的一些总体说明

### 配置,插件目录

如果`\ajiho\middleware\SmartyInit::class`初始化中间件被挂载到全局中间件定义文件中,如果此时
在`smarty.php`配置文件中配置了`prefix`参数，那么会在配置和插件目录上自动加上`prefix`前缀以示区分
如`/smarty/configs`会变成`/smarty/prefix/configs`,因此在你确实需要编写自己的smarty配置和插件的情况
下要根据你`SmartyInit`初始化中间件的位置来分别手动创建目录。当然了,我们一般不会使用这种方式来进行配置
因为要读取配置文件我们一般直接在框架的config目录里面就定义配置就行了,实际上我完全不做这个配置都行，
但是我们前面讲到过，虽然是封装的smarty但是不会阉割它的功能，毕竟有剑不用和没有剑是两回事，而且也是对
smarty忠实用户的一种负责。

### 编译,缓存目录

同样的这两个目录也根据你初始化位置的不同,会在不同的目录生成编译和缓存文件,当挂载到
应用中间件时，它会在上面的基础上加上应用名以示区分,这样的设计是避免多应用开发时生成的缓存、编译文件
混合在一起。


如在`admin`应用中间件初始化文件中进行初始化,会在runtime目录下再自动加上应用名`admin`进行分隔
~~~
/runtime/admin/smarty/compile
/runtime/admin/smarty/cache
~~~

如在`home`应用中间件初始化文件中进行初始化,会在runtime目录下再自动加上应用名`home`进行分隔
~~~
/runtime/home/smarty/compile
/runtime/home/smarty/cache
~~~

# 反馈

开发过程发现有任何问题，欢迎大家提交Issue。



