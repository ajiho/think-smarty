<?php


namespace ajiho\middleware;

use think\facade\Config;
use think\facade\App;

class SmartyInit
{

    public function handle($request, \Closure $next)
    {
        //创建smarty对象
        $smarty = new \Smarty();
        //模板基本目录
        $smarty->setTemplateDir(app_path() . 'view/');
        $smarty->caching = Config::get('smarty.caching');
        //缓存周期
        $smarty->cache_lifetime = Config::get('smarty.cache_lifetime');
        //空格策略
        $smarty->setAutoLiteral(Config::get('smarty.auto_literal'));
        //左右分隔符
        $smarty->setLeftDelimiter(Config::get('smarty.left_delimiter'));
        $smarty->setRightDelimiter(Config::get('smarty.right_delimiter'));
        //编译目录
        $smarty->setCompileDir(runtime_path() . 'smarty/compile/');
        //缓存目录
        $smarty->setCacheDir(runtime_path() . 'smarty/cache/');
        //配置目录
        $smarty->setConfigDir(app_path() . 'smarty/configs/');
        //插件目录
        $smarty->addPluginsDir(app_path() . 'smarty/plugins/');
        // 绑定类实例
        App::bind('smarty', $smarty);
        //继续执行
        return $next($request);
    }
}