<?php

require_once 'conf.php';
$term = trim(strip_tags($_GET['term']));//retrieve the search term that autocomplete sends
$db = mysql_connect($GLOBALS["db_host"], $GLOBALS["db_user"], $GLOBALS["db_pwd"])
or die("Connection Error: " . mysql_error());
mysql_select_db('asterisk') or die("Error connecting to db.");
$qstring = "SELECT name FROM pin WHERE name LIKE '%".$term."%' order by name";
$result = mysql_query($qstring);//query the database for entries containing the term

while ($row = mysql_fetch_array($result,MYSQL_ASSOC))//loop through the retrieved values
{
    //echo $row['name']."\n";
    $row_set[] = $row['name'];//build an array
}
echo json_encode($row_set);
?>