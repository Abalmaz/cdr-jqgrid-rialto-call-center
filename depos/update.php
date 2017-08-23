<?php
require_once 'conf.php';
    //читаем новые значения
$date = $_POST['date'];
$type_dep = $_POST['type_dep'];
$amount = $_POST['amount'];
$Junior = $_POST['Junior'];
$Senior = $_POST['Senior'];
//$name = $_POST['name'];
//$Platform = $_POST['Platform'];
//$id_platform = $_POST['id_platform'];
$id=$_POST['id'];
$clients_id=$_POST['clients_id'];
$operation = $_POST['oper']; //can be “add” “edit” “del”

//$clients_id  = isset($_REQUEST['clients_id']) ? $_REQUEST['clients_id'] : null;

$db = mysql_connect($GLOBALS["db_host"], $GLOBALS["db_user"], $GLOBALS["db_pwd"])
or die("Connection Error: " . mysql_error());
mysql_select_db('asterisk') or die("Error connecting to db.");

if ($operation == 'edit')
{
$SQL = "UPDATE deposits SET date='$date', type_dep='$type_dep', amount='$amount', Junior='$Junior',Senior='$Senior', clients_id='$clients_id'
where id='$id'";
$result = mysql_query($SQL) or die("Couldn't execute query." . mysql_error());}

else if ($operation == 'add') {
    $SQL="INSERT INTO deposits(date, type_dep, amount, Junior, Senior, clients_id) VALUES ('$date', '$type_dep', '$amount', '$Junior', '$Senior', '$clients_id')";
    $result = mysql_query($SQL) or die("Couldn't execute query." . mysql_error());
}
else if($operation == 'del')
{
    $result = mysql_query("delete from deposits WHERE id = '$id'");
}

mysql_close($db);
