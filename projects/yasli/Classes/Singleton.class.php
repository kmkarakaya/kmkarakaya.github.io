<?php
namespace Classes;
/**
 * SINGLETON PATTERN
 * 
 * CLASSIN CONSTRUCTUNDA PARAMETRESI YOKSA KULLANILIR
 * 
 * @author Serhat ÖZDAL
 */
trait Singleton
{
    /**
     * SINGLETON DEGISKEN
     * 
     * @var object
     */
    private static $instance;
    /**
     * SINGLETON METOD
     * 
     * @return Validate
     */
    public static function &getInstance(){
        if(!(self::$instance instanceof self)){
            self::$instance = new self();
        }
        return self::$instance;
    }
}