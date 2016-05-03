<?php
/**
 * Created by PhpStorm.
 * User: jon
 * Date: 4/30/16
 * Time: 10:11 PM
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
            Database::updateSuperheroPwr($id, $val);
        }
    }
    $table  = Database::getSuperheroHTML($_GET["id"]);
}
else
{
    $table  = Database::getSuperheroHTML($_GET["id"]);
}

$body = "<form method='POST' action='superheros.php' enctype='application/x-www-form-urlencoded'>" .
    $table .
    "</form>";

echo buildHTMLPage($body);