<?php
// TODO - Session starten
session_start();
session_regenerate_id(true);
// TODO - Session leeren
$_SESSION = array();
session_destroy();
// TODO - Weiterleiten auf login.php
header('Location: login.php');
?>