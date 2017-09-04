<?php
require_once 'conf.php';
session_start();

$user_create = $_SESSION['name'];
$date_create = date('Y-m-d H:i:s');
//читаем новые значения
$clients_id = $_GET['clients_id'];

$team=$_GET['team'];

$date_call = $_GET['date_call'];
$task = $_GET['task'];
$operation = $_GET['oper'];

$db = mysql_connect($GLOBALS["db_host"], $GLOBALS["db_user"], $GLOBALS["db_pwd"])
or die("Connection Error: " . mysql_error());
mysql_select_db('asterisk') or die("Error connecting to db.");
mysql_set_charset('utf8');
if ($operation == 'add')
{
    $SQL = "INSERT INTO schedule (clients_id, date_create, user_create, date_call, task, result) 
        VALUES ('$clients_id', '$date_create', '$user_create', '$date_call', '$task', 'not done')";
    $result = mysql_query($SQL) or die("Couldn't execute query." . mysql_error());
}

mysql_close($db);
