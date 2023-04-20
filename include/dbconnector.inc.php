<?php

// TODO - mit eigener Datenbak verbinden
$host = 'localhost'; // host
$username = '151_socialMedia@localhost'; // username
$password = 'asd123'; // password
$database = 'OnlineBanking'; // database

// mit Datenbank verbinden
$mysqli = new mysqli($host, $username, $password, $database);

// Fehlermeldung, falls Verbindung fehl schlägt.
if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') '. $mysqli->connect_error);
}
