<?php

// Autoload 自动载入
require '../vendor/autoload.php';

$dbc= require '../config/database.php';
$GLOBALS['db'] = new \medoo($dbc);

// 路由配置
require '../config/routes.php';
