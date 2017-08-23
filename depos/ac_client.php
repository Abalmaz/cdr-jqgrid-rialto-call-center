<?php

require_once 'conf.php';
$term = trim(strip_tags($_GET['term']));//retrieve the search term that autocomplete sends
$db = mysql_connect($GLOBALS["db_host"], $GLOBALS["db_user"], $GLOBALS["db_pwd"])
or die("Connection Error: " . mysql_error());
mysql_select_db('asterisk') or die("Error connecting to db.");
$qstring = "SELECT id_platform as label, platform, name, id FROM clients WHERE id_platform LIKE '".$term."%' order by id_platform";
$result = mysql_query($qstring);//query the database for entries containing the term

while ($row = mysql_fetch_array($result,MYSQL_ASSOC))//loop through the retrieved values
{
    //echo $row['id_platform']."\n";
    $row['name']=utf8_encode(stripslashes($row['name']));
    $row['platform']=utf8_encode(stripslashes($row['platform']));
    $row['label']=(int)$row['label'];
    $row_set[] = $row;//build an array
}
//echo $row_set['id_platform'];
echo json_encode($row_set);

?>