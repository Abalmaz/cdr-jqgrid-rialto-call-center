<?php
require_once 'conf.php';
session_start();
$team=$_SESSION['team'];
$user_create = $_SESSION['name'];
    //читаем новые значения
$id = $_GET['id'];
$result = $_GET['result'];
$note = $_GET['note'];

$client_id = $_GET['client_id'];
$date_call = $_GET['date_call'];
$task = $_GET['task'];

$date_create = date('Y-m-d H:i:s');
$operation = $_GET['oper']; //can be “add” “edit” “del”

$db = mysql_connect($GLOBALS["db_host"], $GLOBALS["db_user"], $GLOBALS["db_pwd"])
or die("Connection Error: " . mysql_error());
mysql_select_db('asterisk') or die("Error connecting to db.");
mysql_set_charset('utf8');
if ($operation == 'add')
{
$SQL = "INSERT INTO schedule (clients_id, date_create, user_create, date_call, task, result) 
        VALUES ('$client_id', '$date_create', '$user_create', '$date_call', '$task', 'not done')";
$result = mysql_query($SQL) or die("Couldn't execute query." . mysql_error());
}
if ($operation == 'edit')
{
    $SQL = "UPDATE schedule SET result='$result', user_exec = '$user_create', date_exec='$date_create' where schedule.id='$id'";
    $SQL_note = "UPDATE clients SET note='$note' where clietnts.id='$clients_id'";
    $result = mysql_query($SQL) or die("Couldn't execute query." . mysql_error());
}
mysql_close($db);
