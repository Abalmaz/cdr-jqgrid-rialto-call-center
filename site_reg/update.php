<?php
require_once 'conf.php';
    //читаем новые значения
$uid = $_POST['id'];
$status = $_POST['status'];
$note = $_POST['comments'];
$modify_date = date('Y-m-d H:i:s');
$operation = $_POST['oper']; //can be “add” “edit” “del”

$db = mysql_connect($GLOBALS["db_host"], $GLOBALS["db_user"], $GLOBALS["db_pwd"])
or die("Connection Error: " . mysql_error());
mysql_select_db('asterisk') or die("Error connecting to db.");
mysql_set_charset('utf8');
if ($operation == 'edit')
{
$SQL = "UPDATE site_registration SET  status='$status', date_modify='$modify_date', comments='$note' where uid='$uid'";
$result = mysql_query($SQL) or die("Couldn't execute query." . mysql_error());
}

mysql_close($db);
