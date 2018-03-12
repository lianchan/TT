<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2017/11/9
 * Time: 下午7:05
 */

namespace TT\Core\Component\Error;


use TT\Core\AbstractInterface\ExceptionHandlerInterface;
use TT\Core\Component\Logger;
use TT\Core\Http\Request;
use TT\Core\Http\Response;

class ExceptionHandler implements ExceptionHandlerInterface
{

    function handler(\Exception $exception)
    {
        // TODO: Implement handler() method.
    }

    function display(\Exception $exception)
    {
        // TODO: Implement display() method.
        if(Request::getInstance()){
            Response::getInstance()->write(nl2br($exception->getMessage().$exception->getTraceAsString()));
        }else{
            Logger::getInstance('error')->console($exception->getMessage().$exception->getTraceAsString(),false);
        }
    }

    function log(\Exception $exception)
    {
        // TODO: Implement log() method.
        Logger::getInstance('error')->log($exception->getMessage()." ".$exception->getTraceAsString());
    }
}