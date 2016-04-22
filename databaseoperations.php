<?php
require("DemoCreds.php");
echo $_POST['method']();
function sanitize($str, $quotes = ENT_NOQUOTES)
{
    $str = htmlspecialchars($str, $quotes);
    return $str;
}
function getDatabases()
{
    // retrieve and sanitize posted values.
    if (isset($_POST['server']))
        $server = json_decode(sanitize($_POST['server']));
    if (isset($_POST['username']))
        $username = json_decode(sanitize($_POST['username']));
    if (isset($_POST['password']))
        $password = json_decode(sanitize($_POST['password']));
    $databaseNames = array();
    $dbConn = mysqli_connect($server, $username, $password);
    $query = "SHOW DATABASES";
    $result = $dbConn->query($query);
    if($result)
    {
        while($row = $result->fetch_array())
            array_push($databaseNames, $row[0]);
    }
    $return = new stdPokemon;
    $return->credentials = $server + " " + $username + " " + $password;
    $return->success = true;
    $return->errorMessage = "";
    $return->data['database_names'] = $databaseNames;
    $json = json_encode($return);
    return $json;
}
function insertPokemon()
{
    if (isset($_POST['Name']))
        $Name = json_decode(sanitize($_POST['Name']));
    if (isset($_POST['Type']))
        $Type = json_decode(sanitize($_POST['Type']));
    if (isset($_POST['Number']))
        $Number = json_decode(sanitize($_POST['Number']));
    $dbConn = mysqli_connect(demoServer(), demoUsername(), demoPassword(), demoDB());
    if ($dbConn->connect_error)
        die("Connection failed: " . $dbConn->connect_error);
    $query = "INSERT INTO Pokemon ( Name, Type, Number ) " .
        "VALUES ( '" .
        $Name . "', '" .
        $Type . "', '" .
        $Number . "' );";
    $result = $dbConn->query($query);
    $return = new stdPokemon;

    $return->querystring = $query;
    if($result)
        $return->success = true;
    else
        $return->success = false;
    return json_encode($return);
}
function updatePokemon()
{
    if (isset($_POST['GenerationID']))
        $GenerationID = json_decode(sanitize($_POST['ID']));
    if (isset($_POST['newName']))
        $newName = json_decode(sanitize($_POST['newName']));
    if (isset($_POST['newType']))
        $newType = json_decode(sanitize($_POST['newType']));
    if (isset($_POST['newNumber']))
        $newNumber = json_decode(sanitize($_POST['newNumber']));
    $dbConn = mysqli_connect(demoServer(), demoUsername(), demoPassword(), demoDB());
    if ($dbConn->connect_error)
        die("Connection failed in updatePokemon()");
    $query = "UPDATE Pokemon " .
        "SET Name='" . $newName .
        "' SET Type='" . $newType .
        "' SET Number='" . $newNumber .
        "' WHERE GenerationID=" . $GenerationID;
    $result = $dbConn->query($query);
    $return = new stdPokemon;
    $return->querystring = $query;
    if ($result)
        $return->success = true;
    else
        $return->success = false;
    return json_encode($return);
}
function getPokemon()
{
    $dbConn = mysqli_connect(demoServer(), demoUsername(), demoPassword(), demoDB());
    $query = "SELECT * FROM Pokemon_List";
    $result = $dbConn->query($query);
    if ($dbConn->connect_error)
    {
        $return = new StdPokemon(); // Added
        $return->connect_error = "Connection failed: " . $dbConn -> connect_error;
        $return->success = false;
        return json_encode($return);
    }
    $pokemon = array();
    if ($result)
    {
        while ($row = $result->fetch_array())
        {
            $allColumns = array();
            for ($i = 0; $i < 3; $i++)
                array_push($allColumns, $row[$i]);
            array_push($pokemon, $allColumns);
        }
    }
    $return = new StdPokemon();
    $return->success = true;
    $return->pokemon = $pokemon;
    $return->querystring = $query;
    $return->credentials =
        demoUsername() . " " .
        demoPassword() . " " .
        demoDB () . " on ".
        demoServer();
    return json_encode($return);
}
