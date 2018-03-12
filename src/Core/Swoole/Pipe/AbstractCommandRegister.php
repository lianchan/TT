<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2017/10/23
 * Time: 上午2:38
 */

namespace TT\Core\Swoole\Pipe;


abstract class AbstractCommandRegister
{
    abstract function register(CommandList $commandList);
}