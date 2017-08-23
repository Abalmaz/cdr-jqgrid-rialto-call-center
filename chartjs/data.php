<?php
//$today = date('Y-m-d');

require_once 'conf.php';

// connect to the database
$db = mysql_connect($GLOBALS["db_host"], $GLOBALS["db_user"], $GLOBALS["db_pwd"])
or die("Connection Error: " . mysql_error());
mysql_select_db('asterisk') or die("Error connecting to db.");
mysql_set_charset('utf8');

$sql="select cdr.userfield,
round (SUM(cdr.duration)/60,0) minutes,
counter.itog calls
from cdr,
      (select count(id) itog, userfield
      from cdr where
             DAY(calldate) = DAY(NOW()) AND MONTH(calldate) = MONTH(NOW()) 
 			      AND YEAR(calldate) = YEAR(NOW())
            AND userfield <> '' group by userfield) counter
where cdr.disposition='ANSWERED'
and DAY(calldate) = DAY(NOW()) AND MONTH(calldate) = MONTH(NOW()) 
AND YEAR(calldate) = YEAR(NOW())
and cdr.userfield=counter.userfield
and cdr.group ='Group_1'
group by cdr.userfield order by counter.itog desc, round (SUM(cdr.duration)/60,0) desc limit 10;";
$result = mysql_query( $sql ) or die("Couldn't execute query.".mysql_error());
$data = array();
while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    $data[]=$row;
}

echo json_encode($data);



mysql_close($db);
