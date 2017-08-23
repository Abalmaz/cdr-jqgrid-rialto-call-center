<?php
require_once 'conf.php';

session_start();

//// connect to the database
$db = mysql_connect($GLOBALS["db_host"], $GLOBALS["db_user"], $GLOBALS["db_pwd"])
or die("Connection Error: " . mysql_error());
mysql_select_db('asterisk') or die("Error connecting to db.");
mysql_set_charset('utf8');


    $SQL="SELECT type_dep, count(amount) kol_dep, sum(amount) sum_dep, round(sum(amount)/count(amount),2) average
      FROM deposits
      where MONTH(date) = MONTH(NOW()) AND YEAR(date) = YEAR(NOW())
      group by type_dep;";
$result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());

if ($limit < 0) $limit = 0;

$start = ($limit * $page) - $limit;
if ($start < 0) $start = 0;

$count = mysql_num_rows($result);
if ($count > 0) {
    $total_pages = ceil($count / $limit);
} else {
    $total_pages = 0;
}

if ($page > $total_pages) {
    $page = $total_pages;
}

$responce->page    = $page;
$responce->total   = $total_pages;
$responce->records = $count;

mysql_data_seek($result, $start);
for ($i = 0; $row = mysql_fetch_assoc($result); $i++) {
    if (($limit > 0) && ($i >= $limit)) break;
    $responce->rows[$i]['id']   = $row['type_dep'];
    $responce->rows[$i]['cell'] = array( $row['type_dep'],$row['kol_dep'],$row['sum_dep'], $row['average']);
}
echo json_encode($responce);



mysql_close($db);

?>