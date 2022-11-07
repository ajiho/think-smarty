<?php


use think\Response;

if (!function_exists('smarty')) {
    /**
     * 返回smarty实例
     * @return mixed|object|smarty|\think\App
     */
    function smarty()
    {
        return app('smarty');
    }
}


if (!function_exists('smarty_assign')) {
    /**
     * 分配Smarty变量
     *
     * @param array|string $tpl_var 模板变量名
     * @param mixed $value 要分配的值
     * @param boolean $nocache 如果为true，则不会缓存该变量的任何输出
     * @return smarty
     */
    function smarty_assign($tpl_var, $value = null, $nocache = false)
    {
        return smarty()->assign($tpl_var, $value, $nocache);
    }
}


if (!function_exists('smarty_fetch')) {
    /**
     * 提取渲染的Smarty模板
     *
     * @param string $template 模板文件或模板对象的资源句柄
     * @param mixed $cache_id 要与此模板一起使用的缓存id
     * @param mixed $compile_id 用于此模板的编译id
     * @param object $parent 下一个更高级别的Smarty变量
     * @return false|string
     * @throws SmartyException
     */
    function smarty_fetch($template = null, $cache_id = null, $compile_id = null, $parent = null)
    {
        return smarty()->fetch($template, $cache_id, $compile_id, $parent);
    }
}


if (!function_exists('smarty_display')) {

    /**
     * 返回一个think\Response对象，输出smarty模板
     *
     * @param string $template 模板文件或模板对象的资源句柄
     * @param mixed $cache_id 要与此模板一起使用的缓存id
     * @param mixed $compile_id 用于此模板的编译id
     * @param object $parent 下一个更高级别的Smarty变量
     * @return Response
     * @throws SmartyException
     */
    function smarty_display($template = null, $cache_id = null, $compile_id = null, $parent = null)
    {
        return Response::create(smarty_fetch($template, $cache_id, $compile_id, $parent));
    }
}
