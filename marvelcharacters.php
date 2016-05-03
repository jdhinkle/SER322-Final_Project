<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 5/2/16
 * Time: 3:06 PM
 */

include("database.php");
include("html_template.php");
Database::init();

$table  = Database::getHeroVillianInfo();

echo buildHTMLPage($table);