<?php

namespace ajiho\smarty;



class Service extends \think\Service
{
    public function register()
    {
        $this->app->bind('smarty', new Smarty($this->app));
    }
}