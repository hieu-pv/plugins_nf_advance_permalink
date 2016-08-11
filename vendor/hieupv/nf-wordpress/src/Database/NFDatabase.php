<?php

namespace NFWP\Database;

use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;

class NFDatabase
{
    /**
     * "Capsule" manager instance. Capsule aims to make configuring
     *
     * @var Illuminate\Database\Capsule\Manager
     */
    private $capsule;

    public function __construct($plugin_file = __FILE__)
    {

        $this->capsule = new Capsule;

        $this->capsule->addConnection([
            'driver'    => 'mysql',
            'host'      => DB_HOST,
            'database'  => DB_NAME,
            'username'  => DB_USER,
            'password'  => DB_PASSWORD,
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ]);
        $this->capsule->setEventDispatcher(new Dispatcher(new Container));
        $this->capsule->setAsGlobal();

        if (method_exists($this, 'up')) {
            register_activation_hook($plugin_file, [$this, 'up']);
        }
        if (method_exists($this, 'down')) {
            register_uninstall_hook($plugin_file, [$this, 'down']);
        }
    }

    /**
     * Gets the "Capsule" manager instance. Capsule aims to make configuring.
     *
     * @return Illuminate\Database\Capsule\Manager
     */
    public function getCapsule()
    {
        return $this->capsule;
    }
}
