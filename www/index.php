<?php session_start();

define('BASEPATH', './core/');
define('START_INDEX', 'START');
//-- соединения с библиотекам
require_once BASEPATH.'libs/lib_config.php';
require_once BASEPATH.'libs/lib.php';
require_once BASEPATH.'libs/lib_mysql.php';
require_once BASEPATH.'libs/lib_security.php';

//-- соединения с конфигурация
require_once BASEPATH.'configs/db_config.php';

//-- 
require_once BASEPATH.'controllers/con_Index.php';

$Index = new Index();
$Index->WebStart();
//test dgdfgdfgfdgfdg ggggghjkkjhgkjh
?>
