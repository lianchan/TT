<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2017/10/29
 * Time: 下午9:36
 */

namespace TT\Core\AbstractInterface;


use TT\Core\Http\Request;
use TT\Core\Http\Response;

interface HttpExceptionHandlerInterface
{
    function handler(\Exception $exception,Request $request , Response $response);
}