<?php


if (!function_exists('smarty')) {
    function smarty()
    {
        try {
            return app('smarty');
        } catch (\Exception $e) {
            echo \ajiho\Smarty::error($e);
            die();
        }
    }
}


if (!function_exists('smarty_assign')) {

    function smarty_assign($tpl_var, $value = null, $nocache = false)
    {
        try {
            return app('smarty')->assign($tpl_var, $value, $nocache);
        } catch (\Exception $e) {
            echo \ajiho\Smarty::error($e);
            die();
        }

    }

}


if (!function_exists('smarty_display')) {

    function smarty_display($template = null, $cache_id = null, $compile_id = null, $parent = null)
    {
        try {
            return app('smarty')->display($template, $cache_id, $compile_id, $parent);
        } catch (\Exception $e) {
            echo \ajiho\Smarty::error($e);
            die();
        }
    }
}