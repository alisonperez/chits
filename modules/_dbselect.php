<?php
// Rename this file to _dbselect.php and update the values below to match your own database username/password
session_start();
$_SESSION["dbname"] = "chits2"; // Name of the database (probably chits)
$_SESSION["dbuser"] = "root"; // mysql username that you are using to connect to the database
$_SESSION["dbpass"] = "root"; // mysql password that you are using to connect to the database
$conn = mysql_connect("localhost", $_SESSION["dbuser"], $_SESSION["dbpass"]);
$db->connectdb($_SESSION["dbname"]);
?>