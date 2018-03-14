<?php

namespace Core\Phalcon\Session\Adapter;

use Phalcon\Session\Adapter\Libmemcached;
use Phwoolcon\Config;
use Core\Phalcon\Session\AdapterInterface;
use Core\Phalcon\Session\AdapterTrait;

/**
 * Class Memcached
 * @package Core\Phalcon\Session\Adapter
 *
 * @property \Phalcon\Cache\Backend\Libmemcached $_libmemcached
 * @method  \Phalcon\Cache\Backend\Libmemcached getLibmemcached()
 */
class Memcached extends Libmemcached implements AdapterInterface
{
    use AdapterTrait;

    public function __construct(array $options = [])
    {
        $options = array_merge(Config::get('cache.drivers.memcached.options'), $options);
        parent::__construct($options);
    }

    public function flush()
    {
        $this->_libmemcached->flush();
        $this->_libmemcached->delete('_PHCM');
    }
}
