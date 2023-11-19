# think-smarty

基于thinkphp6封装的smarty模板引擎

- [smarty官网文档](https://www.smarty.net/docs/zh_CN/)
- [smarty-github文档](https://smarty-php.github.io/smarty/)
- [smarty-github仓库](https://github.com/smarty-php/smarty)

## 为什么要封装think-smarty

虽然现在流行前后端分离，但是有时难免要用一下mvc这种开发方式做一些小项目,但是think
php的模板引擎([ThinkTemplate](https://www.kancloud.cn/manual/think-template/1286403))
在`phpstorm`中格式化html代码时会各种代码错乱和报错,开发起来特别闹心

### ThinkTemplate

![图片备注](https://gitee.com/ajiho/think-smarty/raw/master/.gitee/img/think-template.gif)

### think-smarty

![图片备注](https://gitee.com/ajiho/think-smarty/raw/master/.gitee/img/think-smarty.gif)

## think-smarty的优点

- smarty是一款历史较长、经过多年发展和优化的模板引擎
- smarty是目前市面上知名的三大php模板引擎之一(Smarty、Twig、Blade)
- smarty由于长期稳定的使用和开发，Smarty可以提供较高的稳定性和可靠性
- **phpstorm官方内置支持smarty语法高亮，格式化，折叠**
- think-smarty会自动根据smarty官方的更新而下载最新的稳定版
- think-smarty简化手动集成的繁琐步骤、配置，开箱即用
- thinkphp框架从6.x开始官方默认就不集成模板引擎,意味着你可以安装自己喜欢的模板引擎(think-smarty是个不错的选择)

# 安装

```
composer require ajiho/think-smarty
```

# 配置文件

安装完毕后会自动生成`/config/smarty.php`

```php
<?php

return [
    // 模板引擎左边标记
    'left_delimiter' => '<{',
    // 模板引擎右边标记
    'right_delimiter' => '}>',
    // 空格策略
    'auto_literal' => false,
    // 开启缓存
    'caching' => false,
    // 缓存周期(开启缓存生效) 单位:秒
    'cache_lifetime' => 120,
    // Smarty工作空间目录名称(该目录用于存放模板目录、插件目录、配置目录)
    'workspace_dir_name' => 'view',
    // 模板目录名
    'template_dir_name' => 'templates',
    // 插件目录名
    'plugins_dir_name' => 'plugins',
    // 配置目录名
    'config_dir_name' => 'configs',
    // 模板编译目录名
    'compile_dir_name' => 'templates_compile',
    // 模板缓存目录名
    'cache_dir_name' => 'templates_cache',
    // 全局输出替换
    'tpl_replace_string'  =>  []
];
```

# phpstorm设置

然后根据配置文件`smarty.php`对`phpstorm`进行相应的设置,就可以舒适的开发啦

`ctrl+alt+s`，搜索`smarty`就可以打开如下设置面板

![图片备注](https://gitee.com/ajiho/think-smarty/raw/master/.gitee/img/phpstorm-setting.png)

注意:设置后要重启phpstorm才会生效

# 助手函数

| 函数名            | 描述                                 |
|----------------|------------------------------------|
| smarty         | 返回smarty对象(可用于调用smarty实例的一些属性和方法等) |
| smarty_assign  | 给视图文件赋值                            |
| smarty_fetch   | 返回一个模板解析后的字符串                      |
| smarty_display | 直接输出模板到客户端                         |

# 模板变量

## 模板赋值

```php
<?php
namespace app\index\controller;

use app\BaseController;

class Index extends BaseController
{
    public function index()
    {   
        //通过应用实例获取smarty给模板赋值
        //$this->app->smarty->assign('name','think-smarty');
        //$this->app->smarty->assign('email','lujiahao@88.com');
        
        //使用应用助手函数app('smarty')
        //app('smarty')->assign('name','think-smarty');
        //app('smarty')->assign('name','think-smarty');
            
        // 上面的方式还是太长,通过助手函数smarty()
        // smarty()->assign('name','think-smarty');
        // smarty()->assign('email','lujiahao@88.com');
        
        // 上面的方式还是太长,直接通过助手函数smarty_assign()一步到位
        // smarty_assign('name','think-smarty');
        // smarty_assign('email','lujiahao@88.com');
        
        // 一个一个赋值太麻烦,直接批量赋值
        smarty_assign([
            'name'  => 'think-smarty',
            'email' => 'lujiahao@88.com'
        ]);
        
        // 模板输出
        return smarty_fetch('index.tpl');
    }
}
```

## 保留变量

`Smarty`提供了一个保留变量`$smarty`,可以用于一些原生php的常用的系统变量的获取

```php
<{ $smarty.const.PHP_VERSION }>
<{ $smarty.server.SERVER_NAME }>
<{ $smarty.get.page}>
<{ $smarty.post.page}>
<{ $smarty.server.SCRIPT_NAME }>
<{ $smarty.env.PATH}>
<{ $smarty.session.user_id}>
<{ $smarty.cookies.name}>
<{ $smarty.request.username}>
```

但是对于thinkphp框架我们知道它的`SESSION`或者一些路由参数，我们用原生的php
是获取不到的，必须要用框架的方法才能获取，因此`think-smarty`也保留了一个
全局变量`$think`(相当于应用实例$app),我们可以用它来快速获取到框架相关的东西

```php
<{ $think->request->param('name') }>
<{ $think->request->root() }>
<{ $think->request->root(true) }>
<{ $think->request->patch('name') }>
<{ $think->request->controller() }>
<{ $think->request->action() }>
<{ $think->request->ext() }>
<{ $think->request->host() }>
<{ $think->request->ip() }>
<{ $think->request->header('accept-encoding') }>
<{ $think->config->get('app.default_app') }>
<{ $think->config->get('app.default_timezone') }>
<{ $think->lang->get('require_name') }>
<{ $think->session->get('index_user.name') }>
<{ $think->http->getName() }>
<{ $think->getRootPath() }>
```

它的原理是其实就是类似下面的操作

```php
<?php
declare (strict_types=1);

namespace app\admin\controller;

use app\BaseController;
use think\App;

class Index extends BaseController
{
    public function index(App $app)
    {

        smarty_assign('think', $app);
        smarty_display('index.tpl');
    }
}
```

因此你要是想对你的应用进行全局变量的赋值,可以创建一个BaseController控制器在构
造函数中使用`smarty_assign`方法赋值即可

# 模板渲染

为了更好的理解`think-smarty`设计的目录结构，我们先看一看,`Smarty`的原生集成

```php
include './smarty/Smarty.class.php';//引入smarty类
$smarty = new Smarty();//实例化smarty

//五配置 两方法
$smarty->setLeftDelimiter("{");  //左定界符
$smarty->setRightDelimiter("}"); //右定界符
$smarty->setTemplateDir("/path/templates");  //.tpl模板目录
$smarty->setCompileDir("/path/templates_c"); //模板编译生成的文件
$smarty->setCacheDir("/path/cache"); //缓存目录
$smarty->setConfigDir("/path/configs"); //配置目录
$smarty->setPluginsDir("/path/plugins"); //插件目录
$smarty->caching = true; //开始缓存
$smarty->cache_lifetime = 120; // 缓存时间

// 程序中使用
$smarty->assign('name','this is smarty');//传参到模板
$smarty->display('index.tpl');//渲染（展示模板）

// 渲染设置的模板路径下的tpl文件 path/templates/index.tpl
<!doctype html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

hello ! { $name }

</body>
</html>
```

所以你项目的目录结构可能是下面这样

![图片备注](https://gitee.com/ajiho/think-smarty/raw/master/.gitee/img/smarty_dir.png)

通过以上示例，我们发现`Smarty`是有自定义自己的配置、插件、编译、缓存、模板目录功能的,虽然
配置、和插件功能用到的几率比较低,但是`think-smarty`的封装不会阉割smarty的功能,
因此`think-smarty`对配置、插件、编译、缓存、模板目录在thinkphp6.x中做了**最佳实践**。

对于缓存和编译目录,放到了项目的`runtime`目录方便项目上线时只要统一给该目录设置
读写权限即可。

```
├─app
├─config
├─extend
├─public
├─route
├─runtime
│ └─templates_cache(用于存放smarty的缓存文件)
│ └─templates_compile(用于存放smarty的编译文件)
│
```

如果是多应用模式,会自动加上应用名称作为区分

```
├─app
├─config
├─extend
├─public
├─route
├─runtime
│ └─index
│   └─templates_cache
│   └─templates_compile
│ └─admin
│   └─templates_cache
│   └─templates_compile
│
```

## 模板路径

对于模板、配置、插件目录默认情况下，think-smarty会自动定位，优先定位应用目
录下的`view`目录作为smarty的工作空间目录

### 单应用模式

```
├─app
│   └─controller
│   └─view (smarty工作空间目录)
│     ├─templates        smarty模板目录
│     │  └─index.tpl     index模板文件
│     ├─configs          smarty配置目录
│     ├─plugins          smarty插件目录
```

### 多应用模式

```
├─app
│  ├─app1 （应用1）
│  │   └─view（smarty工作空间目录）
│  │   	 ├─templates         smarty模板目录
│  │     │  └─index.tpl      index模板文件
│  │ 	 ├─configs           smarty配置目录
│  │ 	 ├─plugins           smarty插件目录
│  │ 
│  └─ app2... （更多应用）
```

第二种方式是视图文件和应用类库文件完全分离，统一放置在根目录下的view目录。

### 单应用模式

```
├─view                     smarty工作空间目录
│   ├─templates            smarty模板目录
│   │  ├─layout            布局目录(示例)
│   │  │ └─main.tpl        用于被继承的父模板文件(示例)
│   │  ├─user              用户模块(示例)
│   │  │ └─index.tpl       用户列表模板文件(示例)
│   │  ├─index.tpl         index模板文件(示例)
│   ├─configs              smarty配置目录
│   ├─plugins              smarty插件目录
```

### 多应用模式

```
├─view                      视图文件目录
│  ├─index（应用名称）        smarty工作空间目录
│  │   ├─templates          smarty模板目录
│  │   │  └─index.tpl       index模板文件
│  │   ├─configs            smarty配置目录
│  │   ├─plugins            smarty插件目录
│  ├─admin（应用名称）        smarty工作空间目录
│  │   ├─templates          smarty模板目录
│  │   │  └─index.tpl       index模板文件
│  │   ├─configs            smarty配置目录
│  │   ├─plugins            smarty插件目录
```

## 模板渲染

```php
<?php
namespace app\index\controller;

use app\BaseController;

class Index extends BaseController
{
    public function index()
    {   
        //方式一
        $this->app->smarty->assign('name','think-smarty')
        return $this->app->smarty->fetch('index.tpl')
        
        //方式二
        app('smarty')->assign('name','think-smarty')
        return app('smarty')->fetch('index.tpl')
        
        //方式三
        smarty_assign('name','think-smarty');
        return smarty_fetch('index.tpl');
        
        //方式四
        $name = 'think-smarty';
        return smarty_fetch('index.tpl',compact('name'));
        
        
        //方式五
        $name = 'think-smarty';
        return smarty_display('index.tpl',compact('name'));
    }
}
```

PS:`smarty_display()`方法如果是在最后一行可以省略`return`

```php
<?php
namespace app\index\controller;

use app\BaseController;

class Index extends BaseController
{
    public function index()
    {   
        $name = 'think-smarty';
        smarty_display('index.tpl',compact('name'));
    }
}
```

跨应用渲染模板

```php
smarty_display('index@user/index.tpl');
```

如果你的模板文件位置比较特殊或者需要自定义模板文件的位置，可以采用下面的方式处理

```php
smarty_display('/index.tpl');
smarty_display('/template/public/menu.tpl');
```

只要通过`/`开头的表示从整个项目根目录开始查找模板文件

```
├─app
├─config
├─extend
├─public
├─route
├─template
│ └─public
│   └─menu.tpl     (/template/public/menu.tpl)
├─index.tpl        (/index.tpl)
│
```

## 资源类型

我们知道Smarty支持指定资源类型渲染
https://www.smarty.net/docs/zh_CN/resources.tpl

```php
//明确指定资源类型，等价于smarty_display('index.tpl');
smarty_display('file:index.tpl');
```

也支持指定任意的绝对路径

```php
smarty_display('file:C:/Users/Administrator/Desktop/tp6/index.tpl');
// 包括可以指定非项目路径,可以是磁盘上任何的绝对路径
smarty_display('file:G:/templates/index.tpl');
```

直接渲染内容

```php
$content = '<{$name}>-<{$email}>';
//下次使用时编译
smarty_display('string:'.$content,['name'=>'ajiho','email'=>'lujiahao@88.com']);
//每次都编译
smarty_display('eval:'.$content,['name'=>'ajiho','email'=>'lujiahao@88.com']);
```

# 输出替换

## smarty.php配置文件中添加替换规则即可

```php
'tpl_replace_string'  =>  [
    '__STATIC__'=>'/static',
	'__JS__' => '/static/javascript',
]
```

## 利用smarty的特性-conf配置文件

```
├─view                     smarty工作空间目录
│   ├─templates            smarty模板目录
│   │  ├─user              用户模块(示例)
│   │  │ └─index.tpl       用户列表模板文件(示例)
│   ├─configs              smarty配置目录
│   │  ├─static_path.conf  
```

static_path.conf的内容如下

```
__STATIC__ = '/static'
__JS__  = '/static/javascript'
```

在tpl模板中使用方式有两种方法来读取配置

```php
// 加载配置
<{ config_load file="static_path.conf" }>

//使用配置,方式一
<{#__STATIC__#}>
//使用配置,方式二
<{ $smarty.config.__STATIC__ }>
```

# 反馈

开发过程发现有任何问题，欢迎大家提交Issue。



