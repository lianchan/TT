<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2017/1/23
 * Time: 上午12:44
 */

namespace Core\Http;


use Conf\Config;
use Conf\Event;
use Core\AbstractInterface\AbstractController;
use Core\AbstractInterface\AbstractRouter;
use Core\Component\Di;
use Core\Component\SysConst;
use Core\Http\Message\Status;
use Core\Swoole\Server;
use FastRoute\Dispatcher\GroupCountBased;

class Dispatcher
{
    protected static $selfInstance;
    protected $fastRouterDispatcher;
    protected $controllerPool = array();
    protected $useControllerPool = false;
    protected $controllerMap = array();
    static function getInstance(){
        if(!isset(self::$selfInstance)){
            self::$selfInstance = new Dispatcher();
        }
        return self::$selfInstance;
    }

    function __construct()
    {
        $this->useControllerPool = Config::getInstance()->getConf("CONTROLLER_POOL");
    }

    function dispatch(){
        if(Response::getInstance()->isEndResponse()){
            return;
        }

        $request = Request::getInstance();
        $response = Response::getInstance();
        $request2 = $request->getSwooleRequest();
        $phalcon_application = Server::getInstance()->getPhalconApplication();

        //注册捕获错误函数
//            register_shutdown_function(array($this, 'handleFatal'));
        if ($request2->server['request_uri'] == '/favicon.ico' || $request2->server['path_info'] == '/favicon.ico') {
            return $response->end(true);
        }
        $_SERVER = $request2->server;
        //构造url请求路径,phalcon获取到$_GET['_url']时会定向到对应的路径，否则请求路径为'/'
        $_GET['_url'] = $request2->server['request_uri'];
        if ($request2->server['request_method'] == 'GET' && isset($request2->get)) {
            foreach ($request2->get as $key => $value) {
                $_GET[$key] = $value;
                $_REQUEST[$key] = $value;
            }
        }
        if ($request2->server['request_method'] == 'POST' && isset($request2->post) ) {
            foreach ($request2->post as $key => $value) {
                $_POST[$key] = $value;
                $_REQUEST[$key] = $value;
            }
        }
        if (APPLICATION_ENV == APP_TEST) {
            return $phalcon_application;
        } else {
            $response->write($phalcon_application->handle()->getContent());
        }
    }

}