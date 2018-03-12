<?php

/**
 * Created by PhpStorm.
 * User: YF
 * Date: 16/8/25
 * Time: 上午12:05
 */
namespace Conf;

use Core\Component\Di;
use Core\Component\Spl\SplArray;
use Core\Component\Sys\SysConst;

class Config
{
    private static $instance;
    protected $conf;
    function __construct()
    {
        $conf = $this->sysConf()+$this->userConf();
        $this->conf = new SplArray($conf);
    }
    static function getInstance(){
        if(!isset(self::$instance)){
            self::$instance = new static();
        }
        return self::$instance;
    }
    function getConf($keyPath){
        return $this->conf->get($keyPath);
    }
    /*
    * 在server启动以后，无法动态的去添加，修改配置信息（进程数据独立）
    */
    function setConf($keyPath,$data){
        $this->conf->set($keyPath,$data);
    }

    private function sysConf(){
        return array(
            "SERVER"=>array(
                "LISTEN"         => "0.0.0.0",
                "SERVER_NAME"    => "easyswoole",
                "PORT"           => 9501,
                "RUN_MODE"       => SWOOLE_PROCESS,//不建议更改此项
                 "SERVER_TYPE" => \Core\Swoole\Config::SERVER_TYPE_WEB,//
//                "SERVER_TYPE"    => Core\Swoole\Config::SERVER_TYPE_WEB_SOCKET,// 直播打开
//                'SOCKET_TYPE'    => SWOOLE_TCP,//当SERVER_TYPE为SERVER_TYPE_SERVER模式时有效
                "CONFIG"=>array(
                    'user'             => USER, //当前用户
                    'group'            => USER_GROUP, //当前用户组
                    'task_worker_num'  => 8, //异步任务进程
                    "task_max_request" => 10,
                    'max_request'      => 5000,//强烈建议设置此配置项
                    'worker_num'       => 8,
                    // "log_file"      => Di::getInstance()->get(SysConst::LOG_DIRECTORY)."/swoole.log",
                    'pid_file'         => ROOT . "/Log/pid.pid",
                    'document_root'         => ROOT.'/../public',
                    'enable_static_handler' => true,
                ),
            ),
            "MYSQL" => array(
                'host'     => 'localhost',
                'username' => 'root',
                'password' => 'goodluck888',
                'db'       => 'es',
                'port'     => 3306,
                'charset'  => 'utf8'
            ),
            "REDIS"=>array(
                "host"=>'localhost',
                "port"=>6379,
                "auth"=>''
            ),
            "DEBUG"=>array(
                "LOG"=>1,
                "DISPLAY_ERROR"=>1,
                "ENABLE"=>true,
            ),
            "CONTROLLER_POOL"=>true//web或web socket模式有效
        );
    }

    private function userConf(){
        return array(
            'database' => [
                // 数据库类型
                'type'            => 'mysql',
                // 服务器地址
                'hostname'        => 'localhost',
                // 数据库名
                'database'        => 'es',
                // 用户名
                'username'        => 'root',
                // 密码
                'password'        => 'goodluck888',
                // 端口
                'hostport'        => '',
                // 连接dsn
                'dsn'             => '',
                // 数据库连接参数
                'params'          => [],
                // 数据库编码默认采用utf8
                'charset'         => 'utf8',
                // 数据库表前缀
                'prefix'          => '',
                // 数据库调试模式
                'debug'           => false,
                // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
                'deploy'          => 0,
                // 数据库读写是否分离 主从式有效
                'rw_separate'     => false,
                // 读写分离后 主服务器数量
                'master_num'      => 1,
                // 指定从服务器序号
                'slave_no'        => '',
                // 是否严格检查字段是否存在
                'fields_strict'   => true,
                // 数据集返回类型
                'resultset_type'  => '',
                // 自动写入时间戳字段
                'auto_timestamp'  => false,
                // 时间字段取出后的默认时间格式
                'datetime_format' => 'Y-m-d H:i:s',
                // 是否需要进行SQL性能分析
                'sql_explain'     => false,
                // Builder类
                'builder'         => '',
                // Query类(请勿删除)
                'query'           => '\\think\\db\\Query',
                // 是否需要断线重连
                'break_reconnect' => true,
                // 数据字段缓存路径
                'schema_path'     => '',
                // 模型类后缀
                'class_suffix'    => false,            ]
        );
    }
}