<?php
require("DemoCreds.php");
echo $_POST["method"]();
function sanitize($str, $quotes = ENT_NOQUOTES) {
    $str = htmlspecialchars($str, $quotes);
    return $str;
}
function getPokemon() {
    $dbConn = mysqli_connect(server(), username(), password(), db());
    $query = "select * from Pokemon_List";
    $result = $dbConn->query($query);
    if ($dbConn->connect_error) {
        $return->connect_error = "Connection failed: " . $dbConn->connect_error;
        $return->success = false;
        return json_encode($return);
    }
    $Pokemon = array();
    if ($result) {
        while ($row = $result->fetch_array()) {
            $allColumns = array();
            for ($i = 0; $i < 5; $i++) {
                array_push($allColumns, $row[$i]);
            }
            array_push($Pokemon, $allColumns);
        }
    }
    $return = new stdClass();
    $return->success = true;
    $return->Pokemon = $Pokemon;
    $return->querystring = $query;
    return json_encode($return);
}
function getGeneration() {
    $dbConn = mysqli_connect(server(), username(), password(), db());
    $query = "SELECT * FROM Pokemon_Generations";
    $result = $dbConn->query($query);
    if ($dbConn->connect_error) {
        $return->connect_error = "Connection failed: " . $dbConn->connect_error;
        $return->success = false;
        return json_encode($return);
    }
    $Generation = array();
    if ($result) {
        while ($row = $result->fetch_array()) {
            $allColumns = array();
            for ($i = 0; $i < 2; $i++) {
                array_push($allColumns, $row[$i]);
            }
            array_push($Generation, $allColumns);
        }
    }
    $return = new stdClass();
    $return->success = true;
    $return->Generation = $Generation;
    $return->querystring = $query;
    return json_encode($return);
}
function insertPokemon() {

    if (isset($_POST['GenerationID'])) {
        $GenerationID = json_decode(sanitize($_POST['GenerationID']));
    }
    if (isset($_POST['Name'])) {
        $Name = json_decode(sanitize($_POST['Name']));
    }
    if (isset($_POST['Type'])) {
        $Type = json_decode(sanitize($_POST['Type']));
    }
    if (isset($_POST['Number'])) {
        $Number = json_decode(sanitize($_POST['Number']));
    }
    $dbConn = mysqli_connect(server(), username(), password(), db());
    if ($dbConn->connect_error) {
        die("Connection failed: " . $dbConn->connect_error);
    }
    $query = "INSERT INTO Pokemon_List ( GenerationID, Name, Type, Number ) " .
        "VALUES ( " .
        "'" . $GenerationID . "'," .
        "'" . $Name . "'," .
        "'" . $Type . "'," .
        "'" . $Number . "');";
    $result = $dbConn->query($query);
    $return = new stdClass;
    $return->querystring = (string) $query;
    if ($result) {
        $return->success = true;
    } else {
        $return->success = false;
    }
    return json_encode($return);
}
