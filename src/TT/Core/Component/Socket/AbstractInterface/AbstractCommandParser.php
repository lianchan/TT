<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2017/10/21
 * Time: 下午5:49
 */

namespace TT\Core\Component\Socket\AbstractInterface;



use TT\Core\Component\RPC\Client\Client;
use TT\Core\Component\Socket\Common\Command;

abstract class AbstractCommandParser
{
    abstract function parser(Command $result,AbstractClient $client,$rawData);
}