<?php
require_once 'conf.php';
    //читаем новые значения
$id = $_POST['id'];
$platform = $_POST['platform'];
$id_platform = $_POST['id_platform'];
$name = $_POST['name'];
$phone= $_POST['phone'];
$second_phone= $_POST['second_phone'];
$secr_phone= $_POST['secret_phone'];
$mail= $_POST['mail'];
$birth=$_POST['birthday'];
$country= $_POST['country'];
$team= $_POST['team'];
$status= $_POST['status'];
$lead_name= $_POST['lead_name'];
$documents= $_POST['documents'];
$operation = $_POST['oper']; //can be “add” “edit” “del”

 if ($birth!='') {
     $birthday = date("Y-m-d H:i:s", strtotime($birth));
 }

$db = mysql_connect($GLOBALS["db_host"], $GLOBALS["db_user"], $GLOBALS["db_pwd"])
or die("Connection Error: " . mysql_error());
mysql_select_db('asterisk') or die("Error connecting to db.");
mysql_set_charset('utf8');

if ($secr_phone==''){
    $secret=mysql_query("select id from secret_phone where phone=$phone and id like '700______'");
    $secr_phone=mysql_fetch_row($secret);
    $scr_phone=$secr_phone[0];
     if ($scr_phone == ''){
         $scr=mysql_query("insert into  secret_phone  (id, phone) select max(secret_phone.id)+1, $phone from secret_phone where secret_phone.id like '700______'");
         $secret=mysql_query("select id from secret_phone where phone=$phone and id like '700______'");
         $secr_phone=mysql_fetch_row($secret);
         $scr_phone=$secr_phone[0];
     }
    $secret_phone=$scr_phone;
}

if ($operation == 'edit')
{
$SQL = "UPDATE clients SET platform='$platform', id_platform='$id_platform', name='$name', phone='$phone',
 second_phone='$second_phone', secret_phone='$secret_phone', mail='$mail', birthday='$birthday', team='$team',
 status='$status', lead_name='$lead_name', documents='$documents' where id='$id'";
$result = mysql_query($SQL) or die("Couldn't execute query." . mysql_error());}
else if ($operation == 'add') {
    $SQL = "INSERT INTO clients (platform, id_platform, name, phone, second_phone, secret_phone, mail, birthday,
                  team, status, lead_name, documents) VALUES ('$platform', '$id_platform', '$name', '$phone', '$second_phone', '$secret_phone', '$mail', '$birthday',
    '$team', '$status', '$lead_name', '$documents')";
    $result = mysql_query($SQL) or die("Couldn't execute query." . mysql_error());

    $SQL_rtd = "INSERT INTO rtd (clients_id) SELECT  id from clients where platform='$platform' and id_platform='$id_platform'";
    $result_rtd = mysql_query($SQL_rtd) or die("Couldn't execute query." . mysql_error());

    $query2 = "SELECT code, country FROM code_country";
    $count = mysql_query($query2);
    while ($dest = mysql_fetch_array($count)) {
        for ($i = strlen($phone); $i > 0; $i--) {
            if (substr($phone, 0, $i) == $dest['code']) {
                $country = $dest['country'];
                $sql_c="update clients set country='$country' where platform='$platform' and id_platform='$id_platform' and  phone='$phone';";
                $result = mysql_query($sql_c) or die("Couldn't execute query." . mysql_error());
                break;
            }
        }
    }
}
else if($operation == 'del')
{
    $result = mysql_query("delete from clients WHERE id = '$id'");
}
mysql_close($db);
