<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 5/2/16
 * Time: 3:05 PM
 */

include("database.php");
include("html_template.php");
Database::init();

$table  = Database::getSuperheroCcHqHTML();

echo buildHTMLPage($table);