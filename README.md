# think-smarty

基于thinkphp6封装的smarty模板引擎。


- [smarty-github仓库](https://github.com/smarty-php/smarty)
- [smarty文档](https://smarty-php.github.io/smarty/)
- [smarty中文文档](https://www.smarty.net/docs/zh_CN/)


## smarty的简介

- php模板引擎的鼻祖
- 一直有人在进行维护(支持php8.x)
- 相对于市面上的其它php模板引擎(Blade、Twig、ThinkTemplate)效率更高

## 为什么要使用think-smarty

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
    // 模板引擎左边标记
    'left_delimiter' => '<{',
    // 模板引擎右边标记
    'right_delimiter' => '}>',
];
```

# 使用

## 开启think-smarty

用法和tp6自带的`think\middleware\SessionInit`中间件一样，需要自己开启，
且`api`应用通常也是不需要模板引擎的。

```
\ajiho\middleware\SmartyInit::class
```

## phpstorm设置(重要)

然后根据配置文件`smarty.php`对`phpstorm`进行相应的设置,就可以舒适的开发啦

`ctrl+alt+s`，搜索`smarty`就可以打开如下设置面板

![图片备注](https://img-blog.csdnimg.cn/36d3d5617e65447c9d80a3a0fbe1a8d1.png)

注意:根据设置好后，要关闭项目重新打开phpstorm才会生效。


# 助手函数

| 函数名 | 描述 |
|--|--|
| smarty | 返回smarty对象,可以根据smarty官方文档调用一些方法 |
| smarty_assign | 给视图文件赋值 |
| smarty_fetch | 返回一个模板输出的内容(HTML代码)，而不是直接显示出来 |
| smarty_display | 返回一个response对象 |

# 反馈

开发过程发现有任何问题，欢迎大家提交Issue。



