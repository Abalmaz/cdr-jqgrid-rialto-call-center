<?php
ini_set('log_errors', 'On');
ini_set('error_log', '/var/www/site_reg/errors.log');
require_once 'db.php';

$db = mysql_connect($GLOBALS["db_host"], $GLOBALS["db_user"], $GLOBALS["db_pwd"])
or die("Connection Error: " . mysql_error());
mysql_select_db('asterisk') or die("Error connecting to db.");
mysql_set_charset('utf8');

if (isset($_POST['name']) && !empty($_POST['name']))    { $username = $_POST['name']; if ($username == '') { unset($username);} }    
if (isset($_POST['phone']) && !empty($_POST['phone']))    { $tel = $_POST['phone']; if ($tel == '') { unset($tel);} }  
if (isset($_POST['pochta']) && !empty($_POST['pochta']))    { $mail = $_POST['pochta']; if ($mail == '') { unset($mail);} }

$transaction_id = iconv("UTF-8", "UTF-8", $_POST['transaction_id']);
$language = iconv("UTF-8", "UTF-8", $_POST['language']);
$date_reg = date('Y-m-d H:i:s');

$username = stripslashes($username);
$username = htmlspecialchars($username);

$tel = stripslashes($tel);
$tel = htmlspecialchars($tel);

$mail = stripslashes($mail);
$mail = htmlspecialchars($mail);

$username = trim($username);
$tel = trim($tel);
$pochta = trim($pochta);
$comment = trim($comment);
if (substr ($tel,0,1)=='+')
{
	$tel=substr($tel,1);
}

$sql= "INSERT INTO site_registration(name, phone, email,  id_transaction, language, date_reg, status) VALUES('$username','$tel', '$mail','$transaction_id', '$language', '$date_reg', 'not processed')";
$result = mysql_query ($sql) or die("Couldn't execute query." . mysql_error());;

if ($transaction_id!= NULL ){
$sub_id=mysql_insert_id();
$link = "http://primeadv.go2cloud.org/SP7wn?adv_sub=$sub_id&transaction_id=$transaction_id";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "$link");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$kuku=curl_exec ($ch);
curl_close ($ch);
}

?>