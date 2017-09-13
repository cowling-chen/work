<?php

/* *
 单例模式
 * 单例模式确保某个类只有一个实例，而且自行实例化并向整个系统提供这个实例。
 * 在计算机系统中，线程池、缓存、日志对象、对话框、打印机、数据库操作、显卡的驱动程序常被设计成单例。
 */

class Singleton {
    // 保存类实例的静态成员变量
    private static $_instance;

    //构造方法私有
    private function __construct() {
    }

    //防止对象被复制克隆
    private function __clone() {
        trigger_error('Clone is not allow!', E_USER_ERROR);
    }

    //单例方法,用于访问实例的公共的静态方法
    public static function getInstance() {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    //测试方法

    public function run() {
        print_r('当前时间：' . date('Y-m-d H:i:s'));
    }

}

$singleton = Singleton::getInstance()->run();
