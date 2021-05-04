<?php
namespace Classes;
/**
 * LOADER CLASSI
 * 
 * @author Serhat ÖZDAL
 * @version 2.0
 */
final class AutoLoader
{
    /**
     * OBJELERIN TUTULDUGU ARRAY
     * 
     * @var array
     */
    private static $includeFileArray = array();
    /**
     * CONSTRUCTOR (CONSTRUCT KULLANILDIGINDA SINGLETONU IPTAL ET!)
     */
    public function __construct(){}
    /**
     * SPL_AUTOLOAD_REGISTER METODU
     * 
     * **AYNI CLASSIN TEKRAR REQUIRE EDILMESINI ENGELLER **
     * 
     * @param string $className
     */
    public static function load($className){
        //$file = Config::ROOT_PATH . '/' . str_replace('\\', '/', $className) . '.class.php';
        $file = str_replace('\\', '/', $className) . '.class.php';
        if(!in_array($file, self::$includeFileArray) && file_exists($file)){
            self::$includeFileArray[] = $file;
            require_once $file;
        }
    }
}