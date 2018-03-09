<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2017/10/23
 * Time: 下午3:45
 */

namespace TT\Core\Component\RPC\AbstractInterface;


use TT\Core\Component\RPC\Common\Package;
use TT\Core\Component\Socket\Client\TcpClient;

abstract class AbstractPackageParser
{
    abstract function decode(Package $result,TcpClient $client,$rawData);

    /*
     * must return string
     */
    abstract function encode(Package $res);
}