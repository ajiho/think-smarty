<?php

namespace ajiho\smarty;

use think\App;
use think\Exception;
use think\Response;
use think\facade\Config;
use Smarty;
class ThinkSmarty extends Smarty
{

    private $app;

    // 模板引擎参数
    protected $config = [
        // 开启缓存
        'caching' => false,
        // 缓存周期(开启缓存生效) 单位:秒
        'cache_lifetime' => 120,
        // 空格策略
        'auto_literal' => false,
        // 模板引擎左边标记
        'left_delimiter' => '<{',
        // 模板引擎右边标记
        'right_delimiter' => '}>',
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
    ];

    public function __construct(App $app)
    {

        // 调用父类的构造方法
        parent::__construct();
        
        $this->app = $app;
        //配置合并
        $this->config = array_merge($this->config, Config::get('smarty'));
        //缓存配置
        $this->caching = $this->config['caching'];
        //缓存周期
        $this->cache_lifetime = $this->config['cache_lifetime'];
        //空格策略配置
        $this->setAutoLiteral($this->config['auto_literal']);
        //左分隔符配置
        $this->setLeftDelimiter($this->config['left_delimiter']);
        //右分隔符配置
        $this->setRightDelimiter($this->config['right_delimiter']);
        //设置缓存路径
        $this->setCacheDir($app->getRuntimePath() . $this->config['cache_dir_name'] . DIRECTORY_SEPARATOR);
        //设置编译目录
        $this->setCompileDir($app->getRuntimePath() . $this->config['compile_dir_name'] . DIRECTORY_SEPARATOR);

        //think-smarty保留变量think,为了快速在模板中使用tp的方法
        $this->assign('think', $app);

    }


    public function display($template = null, $cache_id = null, $compile_id = null, $parent = null)
    {
        Response::create($this->fetch($template, $cache_id, $compile_id, $parent))->send();
    }


    public function fetch($template = null, $cacheId = null, $compileId = null, $parent = null)
    {
        if (is_dir($this->app->getAppPath() . $this->config['workspace_dir_name'])) {
            $path = $this->app->getAppPath() . $this->config['workspace_dir_name'] . DIRECTORY_SEPARATOR;
        } else {
            $appName = $this->app->http->getName();
            $path = $this->app->getRootPath() . $this->config['workspace_dir_name'] . DIRECTORY_SEPARATOR . ($appName ? $appName . DIRECTORY_SEPARATOR : '');
        }

        //保存当前路径
        $this->config['templates_path'] = $path . $this->config['template_dir_name'];
        $this->config['configs_path'] = $path . $this->config['config_dir_name'];
        $this->config['plugins_path'] = $path . $this->config['plugins_dir_name'];


        //设置起始模板路径
        $this->setTemplateDir($this->config['templates_path']);
        //设置配置路径
        $this->setConfigDir($this->config['configs_path']);
        //插件目录
        $this->setPluginsDir($this->config['plugins_path']);


        if (strpos($template, ':') === false) { //说明没有明确指定资源类型
            $template = $this->parseTemplate($template);
            //模板不存在 抛出异常
            if (!is_file($template)) {
                throw new Exception('think-smarty:Unable to load template ' . $template);
            }
        }

        try {
            // 调用父类的 fetch() 方法
            return parent::fetch($template, $cacheId, $compileId, $parent);
        } catch (\Exception $e) {
            throw new Exception('think-smarty:' . $e->getMessage());
        }

    }


    /**
     * 解析模板文件
     * @access private
     * @param string $template 模板文件规则
     * @return string
     */
    private function parseTemplate(string $template): string
    {

        // 获取视图根目录
        if (strpos($template, '@')) {
            // 跨模块调用
            list($app, $template) = explode('@', $template);

        }

        if (isset($app)) {
            $viewPath = $this->app->getBasePath() . $app . DIRECTORY_SEPARATOR . $this->config['workspace_dir_name'] . DIRECTORY_SEPARATOR;

            if (is_dir($viewPath)) {
                $path = $viewPath;
            } else {
                $path = $this->app->getRootPath() . $this->config['workspace_dir_name'] . DIRECTORY_SEPARATOR . $app . DIRECTORY_SEPARATOR;
            }

            //设置起始模板路径
            $this->setTemplateDir($path . $this->config['template_dir_name']);
            //设置配置路径
            $this->setConfigDir($path . $this->config['config_dir_name']);
            //插件目录
            $this->addPluginsDir($path . $this->config['plugins_dir_name']);
            //这里必须把模板路径返回
            $path = $path . $this->config['template_dir_name'] . DIRECTORY_SEPARATOR;

        } else {
            $path = $this->config['templates_path'] . DIRECTORY_SEPARATOR;
        }

        if (strpos($template, '/') === 0 ) {
            return $this->app->getRootPath() . str_replace('/', DIRECTORY_SEPARATOR, ltrim($template, '/'));
        } else {
            return $path . str_replace('/', DIRECTORY_SEPARATOR, $template);
        }

    }

}