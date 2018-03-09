<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2017/2/1
 * Time: 上午12:23
 */

namespace TT\Core\Component;

use Phalcon\DI as PhalconDI;

class DI extends PhalconDI
{
    /*
     * 借以实现IOC注入
     */
    protected static $instance;
    static function getInstance(){
        if(!isset(self::$instance)){
            self::$instance = new static();
        }
        return self::$instance;
    }
}