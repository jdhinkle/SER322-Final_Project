<?php

/**
 * Created by PhpStorm.
 * User: jon
 * Date: 4/26/16
 * Time: 8:52 PM
 */
class Database
{

    static $database;

    static function init($dbHost, $dbName, $dbUser, $dbPass) {
        self::$database = new PDO(
            "mysql:host=" . $dbHost .
            ";dbname=" . $dbName,
            $dbUser,
            $dbPass
        );
    }

    # TODO: Add sql query functions

    # Example query function
    static function getSuperhero($id) {
        self::$database->query('Enter query here');
    }
}