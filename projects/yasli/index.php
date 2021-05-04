<?php
/**
 * @author Serhat ÖZDAL
 * @version 1.0
 * @package YASLI
 * @access public
 * @link serhatozdal@gmail.com
 */

// ARKAPLANDA APACHE SERVER VE PHP BILGILERININ GIZLENDIGI KISIM
header("Pragma: secret");
header("X-Powered-By: Serhat OZDAL");
header("Server: secret");

// BITTIGINDE 0 OLACAK
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE & ~E_WARNING);

// ALINACAK OLASI 500 HATASI ICIN ARKAPLANDA OLUSAN HATALAR GOSTERILIR
ini_set("display_errors", "on");
ini_set("display_startup_errors", "on");

// MAX EXECUTION TIME ASILDIGINDA ISLEMIN DEVAM ETMESI ICIN
set_time_limit(120);

require_once 'Classes/AutoLoader.class.php';
require_once 'Classes/PHPExcel.php';
require_once 'Classes/PHPExcel/IOFactory.php';

############################################################
spl_autoload_register('\Classes\AutoLoader::load');
############################################################

const ROOT_PATH = "";
const ROOT_URL = "";

$json = file_get_contents("php://input");

new \Classes\Yasli($_REQUEST, $json);