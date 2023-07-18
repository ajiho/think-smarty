<?php

namespace ajiho\smarty;

use think\Service;

class SmartyService extends Service
{
    public function register()
    {
        $this->app->bind('think_smarty',ThinkSmarty::class);
    }
}