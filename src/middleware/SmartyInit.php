<?php


namespace ajiho\middleware;

use think\facade\Config;
use think\facade\App;

class SmartyInit
{

    public function handle($request, \Closure $next)
    {
        //获取应用路径
        $app_path = app_path();
        //获取运行时目录
        $runtime_path = runtime_path();
        //模板路径
        $templateDir = $app_path . 'view/';
        //编译和缓存
        $compile_dir = $runtime_path . 'smarty/compile/';
        $cache_dir = $runtime_path . 'smarty/cache/';
        //配置和插件
        $configs_dir = $app_path . 'smarty/configs/';
        $plugins_dir = $app_path . 'smarty/plugins/';

        //创建smarty对象
        $smarty = new \Smarty();
        //模板基本目录
        $smarty->setTemplateDir($templateDir);
        $smarty->caching = Config::get('smarty.caching');
        //缓存周期
        $smarty->cache_lifetime = Config::get('smarty.cache_lifetime');
        //空格策略
        $smarty->setAutoLiteral(Config::get('smarty.auto_literal'));
        //左右分隔符
        $smarty->setLeftDelimiter(Config::get('smarty.left_delimiter'));
        $smarty->setRightDelimiter(Config::get('smarty.right_delimiter'));
        //编译目录
        $smarty->setCompileDir($compile_dir);
        //缓存目录
        $smarty->setCacheDir($cache_dir);
        //配置目录
        $smarty->setConfigDir($configs_dir);
        //插件目录
        $smarty->addPluginsDir($plugins_dir);
        // 绑定类实例
        App::bind('smarty', $smarty);
        //继续执行
        return $next($request);
    }
}