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
     *
     * @param $tpl_var
     * @param $value
     * @param $nocache
     * @return smarty
     */
    function smarty_assign($tpl_var, $value = null, $nocache = false)
    {
        return smarty()->assign($tpl_var, $value, $nocache);
    }
}


if (!function_exists('smarty_fetch')) {
    /**
     * @param $template
     * @param $cache_id
     * @param $compile_id
     * @param $parent
     * @return false|string
     * @throws SmartyException
     */
    function smarty_fetch($template = null, $cache_id = null, $compile_id = null, $parent = null)
    {
        return smarty()->fetch($template, $cache_id, $compile_id, $parent);
    }
}


if (!function_exists('smarty_display')) {

    function smarty_display($template = null, $cache_id = null, $compile_id = null, $parent = null)
    {
        return Response::create(smarty_fetch($template, $cache_id, $compile_id, $parent));
    }
}
