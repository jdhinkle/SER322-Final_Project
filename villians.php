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

$table = '';

$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'POST')
{
    foreach ($_POST as $key => $val)
    {
        $pwrPrefix = 'powerlevel_';
        if (preg_match("/^({$pwrPrefix})(.*)/", $key)) {
            $id = substr($key, strlen($pwrPrefix));
            Database::updateVillianPwr($id, $val);
        }
    }
    $table  = Database::getVillianHTML($_GET["id"]);
}
else
{
    $table  = Database::getVillianHTML($_GET["id"]);
}

$body = "<form method='POST' action='villians.php' enctype='application/x-www-form-urlencoded'>" .
    $table .
    "</form>";

echo buildHTMLPage($body);