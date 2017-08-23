<?php
require_once 'conf.php';
    //читаем новые значения
$id = $_POST['id'];
$skype_date = $_POST['skype_date'];
$skype_name = $_POST['skype_name'];
$date_contact = $_POST['date_contact'];
$note = $_POST['note'];
$avg_income = $_POST['avg_income'];
$field_activity = $_POST['field_activity'];
$kind_activity = $_POST['kind_activity'];
$documents= $_POST ['documents'];

$modify_date = date('Y-m-d H:i:s');
$operation = $_POST['oper']; //can be “add” “edit” “del”

$db = mysql_connect($GLOBALS["db_host"], $GLOBALS["db_user"], $GLOBALS["db_pwd"])
or die("Connection Error: " . mysql_error());
mysql_select_db('asterisk') or die("Error connecting to db.");
mysql_set_charset('utf8');
if ($operation == 'edit')
{
$SQL = "UPDATE rtd SET modify_date='$modify_date', skype_date='$skype_date', skype_name='$skype_name',Date_contact='$date_contact', NOTE='$note',
avg_income = '$avg_income', field_activity = '$field_activity', kind_activity = '$kind_activity' where rtd.clients_id='$id'";
$result = mysql_query($SQL) or die("Couldn't execute query." . mysql_error());
$clients = "UPDATE clients SET  documents='$documents' where clients.id='$id'";
$result = mysql_query($clients) or die("Couldn't execute query." . mysql_error());  
}
else if ($operation == 'del')
{
    $SQL = "delete from rtd where rtd.clients_id='$id'";
    $result = mysql_query($SQL) or die("Couldn't execute query." . mysql_error());
}

mysql_close($db);
