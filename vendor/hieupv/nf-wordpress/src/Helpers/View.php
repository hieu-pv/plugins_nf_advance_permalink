<?php

namespace NFWP\Helpers;

use Philo\Blade\Blade;

class View
{
    public function __construct()
    {
        $views       = $this->view;
        $cache       = $this->cache;
        $this->blade = new Blade($views, $cache);
    }
    public function render($view, $data = [])
    {
        if (is_array($data)) {
            echo $this->blade->view()->make($view, $data)->render();
        } else {
            throw new Exception("data pass into view must be an array", 0);
        }
    }
}
