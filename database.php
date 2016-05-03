<?php

/**
 * Created by PhpStorm.
 * User: jon
 * Date: 4/26/16
 * Time: 8:52 PM
 */
class Database
{
    static $dbhost = 'host';
    static $dbname = 'databasename';
    static $dbuser = 'user';
    static $dbpass = 'password';
    static $database;

    static function init()
    {
        try {
            self::$database = new PDO(
                "mysql:host=" . self::$dbhost .
                ";dbname=" . self::$dbname,
                self::$dbuser,
                self::$dbpass
            );
        } catch (Exception $e) {
            echo $e;
        }
    }

    /** Returns superhero info from the database based on a superhero's id
     * @param $id An integer corresponding to a superhero id
     * @return mixed An array containing a row from the superhero table
     */
    static private function getSuperhero($ids = null)
    {
        $filter = "";
        if (is_array($ids)) {
            $filter = " WHERE ID IN " . self::numToSetString($ids);
        }
        else if ($ids !== null) {
            $filter = " WHERE ID = " . $ids;
        }
        return self::$database->query("SELECT * FROM superhero" . $filter . ";");
    }

    static private function getVillian($ids = null)
    {
        $filter = "";
        if (is_array($ids)) {
            $filter = " WHERE VillianID IN " . self::numToSetString($ids);
        }
        else if ($ids !== null) {
            $filter = " WHERE VillianID = " . $ids;
        }
        return self::$database->query("SELECT * FROM villian" . $filter . ";");
    }

    static private function getComicCompany($ids = null)
    {
        $filter = "";
        if (is_array($ids)) {
            $filter = " WHERE ccID IN " . self::numToSetString($ids);
        }
        else if ($ids !== null) {
            $filter = " WHERE ccID = " . $ids;
        }

        return self::$database->query("SELECT * FROM comiccompany" . $filter . ";");
    }

    static private function getAlterEgo($ids = null)
    {
        $filter = "";
        if (is_array($ids)) {
            $filter = " WHERE AlterID IN " . self::numToSetString($ids);
        }
        else if ($ids !== null) {
            $filter = " WHERE AlterID = " . $ids;
        }

        return self::$database->query("SELECT * FROM alter_ego" . $filter . ";");
    }

    /** Prints an html representation of superhero info based on the superhero's id, or all superhero info if
     * id is null.
     * @param $ids A integer or string representation of an integer, or an array of integers or strings, or null.
     * @return A string repesentation of an html table containing the superhero info
     */
    static function getSuperheroHTML($ids = null)
    {
        $htmlTable = "<table class='dbinfo' border='1'>" .
        "<tr><th>Name</th>" .
        "<th>Power Rating</th>" .
        "<th>Team Affiliation</th>" .
        "<th>Sidekick</th>" .
        "<th>Nemesis</th>" .
        "<th>Weakness</th>" .
        "<th>Comic Company</th></tr>";
        $result = null;

        if ($ids === null) {
            $result = self::getSuperhero();
        }
        else if (is_array($ids)) {
            $size = count($ids);
            $newIds = [];
            for ($i = 0; $i < $size; $i++) {
                $id = self::parseID($ids[$i]);
                if ($id !== null) $newIds[] = $id;
            }
            $result = self::getSuperhero($ids);
        }
        else if ($id = self::parseID($ids)) {
            $result = self::getSuperhero($id);
        }

        if ($result !== null) {
            $rows = $result->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                $htmlRow = self::buildSuperheroHTMLRow($row);
                $htmlTable .= $htmlRow;
            }
        }

        $htmlTable .= "</table><input type='submit' value='Update'>";
        return $htmlTable;
    }

    static private function buildSuperheroHTMLRow($PDOStmnt) {
        self::replaceNulls($PDOStmnt);
        return
            "<tr>" .
            "<td><a href='?id={$PDOStmnt['VillianID']}'>{$PDOStmnt['HeroName']}</a></td>" .
            "<td><input type='number' step=0.1 value={$PDOStmnt['PowerRating']} name='powerlevel_{$PDOStmnt['ID']}'></td>" .
            "<td>{$PDOStmnt["TeamAffiliation"]}</td>" .
            "<td>{$PDOStmnt["Sidekick"]}</td>" .
            "<td>{$PDOStmnt["Nemesis"]}</td>" .
            "<td>{$PDOStmnt["Weakness"]}</td>" .
            "<td>{$PDOStmnt["ComicCompany"]}</td>" .
            "</tr>";
    }

    static function getVillianHTML($ids)
    {
        $htmlTable = "<table class='dbinfo' border='1'>" .
            "<tr><th>Name</th>" .
            "<th>Power Rating</th>" .
            "<th>Team Affiliation</th>" .
            "<th>Sidekick</th>" .
            "<th>Nemesis</th>" .
            "<th>Comic Company</th></tr>";
        $result = null;

        if ($ids === null) {
            $result = self::getVillian();
        }
        else if (is_array($ids)) {
            $size = count($ids);
            $newIds = [];
            for ($i = 0; $i < $size; $i++) {
                $id = self::parseID($ids[$i]);
                if ($id !== null) $newIds[] = $id;
            }
            $result = self::getVillian($ids);
        }
        else if ($id = self::parseID($ids)) {
            $result = self::getVillian($id);
        }
        if ($result !== null) {
            $rows = $result->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                $htmlRow = self::buildVillianHTMLRow($row);
                $htmlTable .= $htmlRow;
            }
        }

        $htmlTable .= "</table><input type='submit' value='Update'>";
        return $htmlTable;
    }

    static private function buildVillianHTMLRow($PDOStmnt)
    {
        self::replaceNulls($PDOStmnt);
        return
            "<tr>" .
            "<td><a href='?id={$PDOStmnt['VillianID']}'>{$PDOStmnt["VillName"]}</a></td>" .
            "<td><input type='number' step=0.1 value={$PDOStmnt['PowerRating']} name='powerlevel_{$PDOStmnt['VillianID']}'></td>" .
            "<td>{$PDOStmnt["TeamAffiliation"]}</td>" .
            "<td>{$PDOStmnt["Sidekick"]}</td>" .
            "<td>{$PDOStmnt["Nemesis"]}</td>" .
            "<td>{$PDOStmnt["ComicCompany"]}</td>" .
            "</tr>";
    }

    static function getTeamsAvgHTML() {
        $htmlTable = "<table class='dbinfo' border='1'>" .
            "<tr><th>Team</th>" .
            "<th>Average Power Rating</th>" .
            "</tr>";
        $result = self::$database->query("SELECT TeamAffiliation FROM superhero WHERE TeamAffiliation IS NOT NULL " .
          "UNION SELECT TeamAffiliation FROM villian WHERE TeamAffiliation IS NOT NULL;");
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        foreach($rows as $row) {
            $team =  $row['TeamAffiliation'];
            $result2 = self::$database->query(
                "SELECT ROUND(AVG(SV.PowerRating), 1) AS AvgPwrRating " .
                "FROM (" .
                    "SELECT TeamAffiliation, PowerRating FROM superhero UNION ALL " .
                    "SELECT TeamAffiliation, PowerRating FROM villian " .
                ") AS SV " .
                "WHERE SV.TeamAffiliation = '{$team}';"
            );
            $teamAvg = $result2->fetchAll(PDO::FETCH_ASSOC)["0"]["AvgPwrRating"];
            $htmlTable .= "<tr><td>{$team}</td><td>{$teamAvg}</td></tr>";
        }
        return $htmlTable . "</table>";
    }

    static function getAlterEgoHTML($ids)
    {
        $htmlTable = "<table class='dbinfo' border='1'>" .
            "<tr><th>Alter Ego Name</th>" .
            "<th>Occupation</th>" .
            "<th>Hometown</th>" .
            "<th>Superhero ID</th></tr>";
        $result = null;

        if ($ids === null) {
            $result = self::getAlterEgo();
        }
        else if (is_array($ids)) {
            $size = count($ids);
            $newIds = [];
            for ($i = 0; $i < $size; $i++) {
                $id = self::parseID($ids[$i]);
                if ($id !== null) $newIds[] = $id;
            }
            $result = self::getAlterEgo($ids);
        }
        else if ($id = self::parseID($ids)) {
            $result = self::getAlterEgo($id);
        }

        if ($result !== null) {
            $rows = $result->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                $htmlRow = self::buildAlterEgoHTMLRow($row);
                $htmlTable .= $htmlRow;
            }
        }

        $htmlTable .= "</table>";
        return $htmlTable;
    }

    static function buildAlterEgoHTMLRow($PDOStmnt)
    {
        self::replaceNulls($PDOStmnt);
        return
            "<tr>" .
            "<td>{$PDOStmnt["AlterName"]}</td>" .
            "<td>{$PDOStmnt["Occupation"]}</td>" .
            "<td>{$PDOStmnt["Hometown"]}</td>" .
            "<td>{$PDOStmnt["SuperheroID"]}</td>" .
            "</tr>";
    }

    static function getComicCompanyHTML($ids)
    {
        $htmlTable = "<table class='dbinfo' border='1'>" .
            "<tr><th>Company Name</th>" .
            "<th>Headquarters</th></tr>";
        $result = null;

        if ($ids === null) {
            $result = self::getComicCompany();
        }
        else if (is_array($ids)) {
            $size = count($ids);
            $newIds = [];
            for ($i = 0; $i < $size; $i++) {
                $id = self::parseID($ids[$i]);
                if ($id !== null) $newIds[] = $id;
            }
            $result = self::getComicCompany($ids);
        }
        else if ($id = self::parseID($ids)) {
            $result = self::getComicCompany($id);
        }

        if ($result !== null) {
            $rows = $result->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                $htmlRow = self::buildComicCompanyHTMLRow($row);
                $htmlTable .= $htmlRow;
            }
        }

        $htmlTable .= "</table>";
        return $htmlTable;
    }

    static function buildComicCompanyHTMLRow($PDOStmnt)
    {
        self::replaceNulls($PDOStmnt);
        return
            "<tr>" .
            "<td>{$PDOStmnt["CompanyName"]}</td>" .
            "<td>{$PDOStmnt["Headquarters"]}</td>" .
            "</tr>";
    }

    static function updateSuperheroPwr($id, $power) {
        $success = false;
        $id = self::parseID($id);
        if ($id && is_numeric($power)) {
            $power = floatval($power);
            self::$database->query("UPDATE superhero
              SET PowerRating = {$power}
              WHERE ID = {$id};");
        }
        return $success;
    }

    static function updateVillianPwr($id, $power) {
        $success = false;
        $id = self::parseID($id);
        if ($id && is_numeric($power)) {
            $power = floatval($power);
            self::$database->query("UPDATE villian
              SET PowerRating = {$power}
              WHERE VillianID = {$id};");
        }
        return $success;
    }

    static function getSuperheroCcHqHTML() {
        $htmlTable = "<table class='dbinfo' border='1'>" .
            "<tr><th>Superhero Name</th>" .
            "<th>Comic Company HQ</th>" .
            "</tr>";
        $result = self::$database->query("SELECT HeroName, comiccompany.Headquarters FROM comiccompany
Join superhero ON comiccompany.CompanyName = superhero.ComicCompany
ORDER by Headquarters;");
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            $htmlTable .= "<tr><td>{$row["HeroName"]}</td><td>{$row["Headquarters"]}</td></tr>";
        }
        return $htmlTable . "</table>";
    }

    static function getSuperheroAlterEgoHTML() {
        $htmlTable = "<table class='dbinfo' border='1'>" .
            "<tr><th>Superhero Name</th>" .
            "<th>Alter Ego Name</th>" .
            "</tr>";
        $result = self::$database->query("SELECT HeroName, AlterName From superhero
JOIN alter_ego ON superhero.ID = alter_ego.SuperheroID;");
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            $htmlTable .= "<tr><td>{$row["HeroName"]}</td><td>{$row["AlterName"]}</td></tr>";
        }
        return $htmlTable . "</table>";
    }

    static function getPowerfulVillians() {
        $htmlTable = "<table class='dbinfo' border='1'>" .
            "<tr><th>Villian Name</th>" .
            "<th>Power Rating</th>" .
            "</tr>";
        $result = self::$database->query("SELECT VillName, PowerRating FROM villian
WHERE Powerrating > 7 and Nemesis = 'Avengers';");
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            $htmlTable .= "<tr><td>{$row["VillName"]}</td><td>{$row["PowerRating"]}</td></tr>";
        }
        return $htmlTable . "</table>";
    }

    static function getHeroVillianInfo() {
        $htmlTable = "<table class='dbinfo' border='1'>" .
            "<tr><th>Marvel Character</th>" .
            "<th>Comic Company</th>" .
            "</tr>";
        $result = self::$database->query("SELECT HeroName AS Marvel_Character, comiccompany.Headquarters FROM comiccompany
Join superhero ON comiccompany.CompanyName = superhero.ComicCompany
WHERE headquarters <> 'Burbank CA' AND Headquarters <> 'Berkeley CA'
UNION
SELECT VillName AS Marvel_Character, comiccompany.Headquarters FROM comiccompany
Join villian ON comiccompany.CompanyName = villian.ComicCompany
WHERE headquarters <> 'Burbank CA' AND Headquarters <> 'Berkeley CA';");
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            $htmlTable .= "<tr><td>{$row["Marvel_Character"]}</td><td>{$row["Headquarters"]}</td></tr>";
        }
        return $htmlTable . "</table>";
    }

    static private function parseID($str)
    {
        $num = intval($str);
        if (is_numeric($str) && is_int($num)) {
            return $num;
        }
        return null;
    }

    static private function replaceNulls($arr)
    {

        foreach ($arr as $key => $val) {
            if ($val === null) {
                $arr[$key] = "N/A";
            }
        }
        return $arr;
    }

    static private function numToSetString($nums) {
        $set = "(";
        $first = true;
        foreach ($nums as $num) {
            if ($first) {
                $first = false;
            }
            else {
                $set .= ", ";
            }
            $set .= $num;
        }
        $set .= ")";
        return $set;
    }
}
