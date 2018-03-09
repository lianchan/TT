<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2017/1/23
 * Time: 上午1:07
 */
namespace TT\Base\AbstractInterface;


use Core\Component\Di;

abstract class AbstractService
{
    protected $model;

    function __construct()
    {
    }

    public function getModel()
    {
    }
}