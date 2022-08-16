<?php


namespace ajiho\middleware;

use think\facade\Config;
use ajiho\Smarty;
use think\facade\App;

class SmartyInit
{

    public function handle($request, \Closure $next)
    {
        //创建smarty对象
        $smarty = new Smarty();
        //模板基本目录
        $smarty->setTemplateDir('../');
        $smarty->caching = Config::get('smarty.caching');
        //缓存周期
        $smarty->cache_lifetime = Config::get('smarty.cache_lifetime');
        //空格策略
        $smarty->setAutoLiteral(Config::get('smarty.auto_literal'));
        //左右分隔符
        $smarty->setLeftDelimiter(Config::get('smarty.left_delimiter'));
        $smarty->setRightDelimiter(Config::get('smarty.right_delimiter'));
        //获取应用名称
        $app_name = app('http')->getName();
        $prefix = Config::get('smarty.prefix');
        //先处理掉编译和缓存
        if ($app_name != '') {//挂载到应用
            $compile_dir = '../runtime/' . $app_name . '/smarty/compile';
            $cache_dir = '../runtime/' . $app_name . '/smarty/cache';
        } else {
            $compile_dir = '../runtime/smarty/compile';
            $cache_dir = '../runtime/smarty/cache';
        }

        //再处配置和插件
        if ($app_name != '') {//挂载到应用
            $configs_dir = '../smarty/' . $app_name . '/configs';
            $plugins_dir = '../smarty/' . $app_name . '/plugins';
        } else {
            $configs_dir = '../smarty/configs';
            $plugins_dir = '../smarty/plugins';
            if ($prefix != '') {
                $configs_dir = '../smarty/' . $prefix . '/configs';
                $plugins_dir = '../smarty/' . $prefix . '/plugins';
            }
        }
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