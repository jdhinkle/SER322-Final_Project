<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 4/26/16
 * Time: 9:51 PM
 */

include("database.php");
include("html_template.php");
Database::init();

echo buildHTMLPage('Use the links above to navigate.');
