<?php


namespace ajiho;

use think\facade\Env;
use think\Response;


class Smarty extends \Smarty
{


    private static function getError($exception)
    {
        $message = $exception->getMessage();
        if ($message == 'class not exists: smarty') {
            $message = '\ajiho\middleware\SmartyInit::class中间件未安装';
        }
        $error_tpl = '<tr><th scope="row">message:</th><td>%s</td></tr><tr><th scope="row">code:</th><td>%s</td></tr><tr><th scope="row">file:</th><td>%s</td></tr><tr><th scope="row">line:</th><td>%s</td></tr>';
        return sprintf($error_tpl, $message, $exception->getCode(), $exception->getFile(), $exception->getLine());
    }

    private static function getTrace($exception)
    {
        $trace = explode("#", $exception->getTraceAsString());
        array_shift($trace);
        $new_trace = [];
        foreach ($trace as $t) {
            $new_trace[] = preg_replace('/^[0-9]+ /', '', $t);
        }
        $trace_tpl = '<tr><th scope="row">%s</th><td>%s</td></tr>';
        $temp = '';
        //循环遍历数据
        foreach ($new_trace as $k => $v) {
            $temp .= sprintf($trace_tpl, count($new_trace) - $k, $v);
        }
        return $temp;
    }


    public static function error($exception)
    {
        if (Env::get('app_debug')) {
            $error = static::getError($exception);
            $trace = static::getTrace($exception);
            $html = file_get_contents(__DIR__ . '/debug.html');
            $html = str_replace('{$error}', $error, $html);
            return str_replace('{$trace}', $trace, $html);
        }
    }


    public function display($template = null, $cache_id = null, $compile_id = null, $parent = null)
    {
        try {
            return Response::create($this->fetch($template, $cache_id, $compile_id, $parent));
        } catch (\Exception $e) {
            echo Smarty::error($e);
            die();
        }
    }
}