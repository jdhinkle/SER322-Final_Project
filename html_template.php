<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 5/2/16
 * Time: 2:02 PM
 */

function buildHTMLPage($body) {
    return "<!DOCTYPE html>" .
    "<html>" .
    "<head>" .
    "<link rel='stylesheet' href='./style.css' type='text/css' media='all'>" .
    "<title>Superhero App</title>" .
    "</head>" .
    "<header class='header-nav'>" .
        "<h1>Superhero Database</h1>" .
        "<div class='navbar-default'>" .
            "<li><a href='superheros.php'>Superheros</a></li>" .
            "<li><a href='villians.php'>Villians</a></li>" .
            "<li><a href='alteregos.php'>Alter Egos</a></li>" .
            "<li><a href='comiccompanies.php'>Comic Companies</a></li>" .
            "<li><a href='teamavgpower.php'>Teams' Average Power</a></li>" .
            "<li><a href='herocompanyhqs.php'>Heros' Comic Company HQs</a></li>" .
            "<li><a href='heroalterego.php'>Heros' Alter Egos</a></li>" .
            "<li><a href='strongvillians.php'>Strong Villians</a></li>" .
            "<li><a href='marvelcharacters.php'>Marvel Characters</a></li>" .
        "</div>" .
    "</header>" .
    "<body>" .
    $body .
    "</body>" .
    "</html>";
}