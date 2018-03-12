<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2017/10/10
 * Time: 下午10:20
 */

namespace TT\Core\Component\Socket;


use TT\Core\Component\Socket\AbstractInterface\AbstractClient;
use TT\Core\Component\Socket\Client\TcpClient;
use TT\Core\Component\Socket\Client\UdpClient;
use TT\Core\Swoole\Server;

class Response
{
    static function response(AbstractClient $client,$data,$eof = ''){
        if($client instanceof TcpClient){
            if($client->getClientType() == Type::WEB_SOCKET){
                return Server::getInstance()->getServer()->push($client->getFd(),$data);
            }else{
                return Server::getInstance()->getServer()->send($client->getFd(),$data.$eof,$client->getReactorId());
            }
        }else if($client instanceof UdpClient){
             return Server::getInstance()->getServer()->sendto($client->getAddress(),$client->getPort(),$data.$eof);
        }else{
            trigger_error( "client is not validate");
            return false;
        }
    }
}