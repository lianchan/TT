<?php

namespace Core\Phalcon\Session\Adapter;

use Phalcon\Session\Adapter\Files;
use Phwoolcon\Config;
use Core\Phalcon\Session\AdapterInterface;
use Core\Phalcon\Session\AdapterTrait;

class Native extends Files implements AdapterInterface
{
    use AdapterTrait;

    public function __construct($options = null)
    {
        parent::__construct($options);
        $sessionPath = $options['save_path'];
        is_dir($sessionPath) or mkdir($sessionPath, 0755, true);
        session_save_path($sessionPath);
    }

    public function flush()
    {
        foreach (glob($this->_options['save_path'] . '/sess*') as $file) {
            @unlink($file);
        }
    }
}
