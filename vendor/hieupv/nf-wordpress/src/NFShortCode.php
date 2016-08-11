<?php

namespace NFWP;

use Exception;
use Philo\Blade\Blade;

class NFShortCode
{
    public function __construct()
    {
        /**
         * aggregate of all methods
         *
         * @var array
         */
        $methods = get_class_methods($this);

        /**
         * do not create short code with methods in this array
         *
         * @var array
         */
        $ignore = ['__construct', 'render'];

        foreach ($ignore as $method) {
            if (in_array($method, $methods)) {
                $key = array_search($method, $methods);
                array_splice($methods, $key, 1);
            }
        }

        /**
         * create shortcode
         *
         */
        foreach ($methods as $shortcode) {
            add_shortcode($shortcode, [$this, $shortcode]);
        }

        $views       = isset($this->view) ? $this->view : __DIR__ . '/Resources/Views';
        $cache       = isset($this->cache) ? $this->cache : __DIR__ . '/Resources/Cache';
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
