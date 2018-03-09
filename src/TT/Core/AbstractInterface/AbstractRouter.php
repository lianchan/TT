<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2017/2/3
 * Time: 下午8:21
 */

namespace TT\Core\AbstractInterface;
use TT\Core\Http\Request;
use TT\Core\Http\Response;
use FastRoute\DataGenerator\GroupCountBased;
use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std;

abstract class AbstractRouter
{
    protected $isCache = false;
    protected $cacheFile;
    private $routeCollector;
    function __construct()
    {
        $this->routeCollector = new RouteCollector(new Std(),new GroupCountBased());
        $this->register($this->routeCollector);
    }

    abstract function register(RouteCollector $routeCollector);
    function getRouteCollector(){
        return $this->routeCollector;
    }
    function request(){
        return Request::getInstance();
    }
    function response(){
        return Response::getInstance();
    }
}