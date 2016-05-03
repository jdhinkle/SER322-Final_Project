<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 5/2/16
 * Time: 2:00 PM
 */

include("database.php");
include("html_template.php");
Database::init();

$table  = Database::getTeamsAvgHTML();

echo buildHTMLPage($table);