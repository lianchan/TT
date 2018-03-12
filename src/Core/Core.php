<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2017/1/22
 * Time: 下午9:54
 */

namespace TT\Core;


use TT\Conf\Config;
use TT\Conf\Event;
use TT\Core\AbstractInterface\ErrorHandlerInterface;
use TT\Core\Component\Di;
use TT\Core\Component\Error\Trigger;
use TT\Core\Component\Sys\ErrorHandler;
use TT\Core\Component\Spl\SplError;
use TT\Core\Component\SysConst;
use TT\Core\Http\Request;
use TT\Core\Http\Response;
use TT\Core\Swoole\Server;
use TT\Core\Utility\File;


class Core
{
    protected static $instance;
    private $preCall;
    function __construct($preCall)
    {
        $this->preCall = $preCall;
    }

    static function getInstance(callable $preCall = null){
        if(!isset(self::$instance)){
            self::$instance = new static($preCall);
        }
        return self::$instance;
    }

    function run(){
        Server::getInstance()->startServer();
    }

    /*
     * initialize frameWork
     */
    function frameWorkInitialize(){
        if(phpversion() < 5.6){
            die("php version must >= 5.6");
        }
        $this->defineSysConst();
        $this->registerAutoLoader();
        $this->preHandle();
        Event::getInstance()->frameInitialize();
//        $this->sysDirectoryInit();
        Event::getInstance()->frameInitialized();
        $this->registerErrorHandler();
        return $this;
    }

    private function defineSysConst(){
        defined('ROOT') or define('ROOT',realpath(__DIR__.'/../'));
        defined('USER') or define('USER',trim(shell_exec('whoami')));
        defined('USER_GROUP') or define('USER_GROUP',trim(shell_exec('groups '.USER)));
    }
//    private function sysDirectoryInit(){
//        //创建临时目录
//        $tempDir = Di::getInstance()->get(SysConst::TEMP_DIRECTORY);
//        if(empty($tempDir)){
//            $tempDir = ROOT."/Temp";
//            Di::getInstance()->set(SysConst::TEMP_DIRECTORY,$tempDir);
//        }
//        if(!File::createDir($tempDir)){
//            die("create Temp Directory:{$tempDir} fail");
//        }else{
//            //创建默认Session存储目录
//            $path = $tempDir."/Session";
//            File::createDir($path);
//            Di::getInstance()->set(SysConst::SESSION_SAVE_PATH,$path);
//        }
//        //创建日志目录
//        $logDir = Di::getInstance()->get(SysConst::LOG_DIRECTORY);
//        if(empty($logDir)){
//            $logDir = ROOT."/Log";
//            Di::getInstance()->set(SysConst::LOG_DIRECTORY,$logDir);
//        }
//        if(!File::createDir($logDir)){
//            die("create log Directory:{$logDir} fail");
//        }
//        Config::getInstance()->setConf("SERVER.CONFIG.log_file",$logDir."/swoole.log");
//        Config::getInstance()->setConf("SERVER.CONFIG.pid_file",$logDir."/pid.pid");
//    }

    private static function registerAutoLoader(){
        require_once __DIR__."/AutoLoader.php";
        $loader = AutoLoader::getInstance();
        $loader->registerNamespaces(
            [
                'TT\Core' => ROOT . "/Core/",
                'TT\Conf' => ROOT . "/Conf/",
                'TT\Base' => ROOT . "/Base/",
            ]
        );
        $loader->register();
    }

    private function registerErrorHandler(){
        $conf = Config::getInstance()->getConf("DEBUG");
        if(true === $conf['ENABLE']){
            ini_set("display_errors", "On");
            error_reporting(E_ALL | E_STRICT);
            set_error_handler(function($errorCode, $description, $file = null, $line = null, $context = null){
                Trigger::error($description,$file,$line,$errorCode,debug_backtrace());
            });
            register_shutdown_function(function (){
                $error = error_get_last();
                if(!empty($error)){
                    Trigger::error($error['message'],$error['file'],$error['line'],E_ERROR,debug_backtrace());
                    //HTTP下，发送致命错误时，原有进程无法按照预期结束链接,强制执行end
                    if(Request::getInstance()){
                        Response::getInstance()->end(true);
                    }
                }
            });
        }
    }
    private function preHandle(){
        if(is_callable($this->preCall)){
            call_user_func($this->preCall);
        }
        Di::getInstance()->set(SysConst::SESSION_NAME, function () {
            return 'TT';
        }, true);
        Di::getInstance()->set(SysConst::VERSION, function () {
            return '0.0.1';
        }, true);
    }
}