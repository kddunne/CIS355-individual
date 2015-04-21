<?php
// Filename: database.php, Kyle Dunne, CIS355, 2015-04-20
// This file is required for every file and is used connect to the database
// Code reused from Multifile CRUD example done in class
class Database
{
    private static $dbName = 'CIS355kddunne' ;
    private static $dbHost = 'localhost' ;
    private static $dbUsername = 'CIS355kddunne';
    private static $dbUserPassword = 'kddunne486661';
     
    private static $cont  = null;
     
    public function __construct() {
        die('Init function is not allowed');
    }
     
    public static function connect()
    {
       // One connection through whole application
       if ( null == self::$cont )
       {     
        try
        {
          self::$cont =  new PDO( "mysql:host=".self::$dbHost.";"."dbname=".self::$dbName, self::$dbUsername, self::$dbUserPassword); 
        }
        catch(PDOException $e)
        {
          die($e->getMessage()); 
        }
       }
       return self::$cont;
    }
     
    public static function disconnect()
    {
        self::$cont = null;
    }
}
?>
