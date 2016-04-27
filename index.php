<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 4/26/16
 * Time: 9:51 PM
 */

include("database.php");

Database::init('dbhost', 'dbname', 'dbuser', 'dbpass');

# example
$id = $_POST["superhero-id"];
Database::getSuperhero($id);