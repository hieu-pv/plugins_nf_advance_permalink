<?php

namespace App;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Log extends Logger
{
    public function __construct()
    {
        parent::__construct('NF_ADVANCE_PERMALINK');
        if (defined('NF_ADVANCE_PERMALINK_DEBUG') && defined('NF_ADVANCE_PERMALINK_LOG_PATH') && NF_ADVANCE_PERMALINK_DEBUG) {
            $this->pushHandler(new StreamHandler(NF_ADVANCE_PERMALINK_LOG_PATH));
        }
    }
    public function write($level, $message, array $context = array())
    {
        if (defined('NF_ADVANCE_PERMALINK_DEBUG') && defined('NF_ADVANCE_PERMALINK_LOG_PATH') && NF_ADVANCE_PERMALINK_DEBUG) {
            $this->log($level, $message, $context);
        }
    }
}
