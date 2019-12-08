<?php
namespace App;
use \PDO;
 
class Db {

    private static $instance = null;

    /**
     * @return PDO
     */

     public static function getPDO(): ?PDO
        {
             $db_dsn = 'mysql:host=localhost;dbname=blog';
             $db_user = 'root';
             $db_pass = 'root';
            /* Option Mysl */
             $options = 
        [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', 
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];
            if(self::$instance === null){
             self::$instance = new PDO($db_dsn, $db_user, $db_pass, $options);
            }
            
            return self::$instance;
        }

}