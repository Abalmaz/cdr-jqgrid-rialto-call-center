<?php
require_once 'conf.php';

session_start();

//// connect to the database
$db = mysql_connect($GLOBALS["db_host"], $GLOBALS["db_user"], $GLOBALS["db_pwd"])
or die("Connection Error: " . mysql_error());
mysql_select_db('asterisk') or die("Error connecting to db.");
mysql_set_charset('utf8');

//read parameters
//$page  = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;          // get the requested page
//$limit = isset($_REQUEST['rows']) ? $_REQUEST['rows'] : 20;         // get how many rows we want to have into the grid
//$sidx  = isset($_REQUEST['sidx']) ? $_REQUEST['sidx'] : 1;          // get index row - i.e. user click to sort
//$sord  = isset($_REQUEST['sord']) ? $_REQUEST['sord'] : "asc";      // get the direction
//if(!$sidx) $sidx =1;


    $SQL="SELECT name, sum(kol) kol_dep, sum(deposit) sum_dep
      FROM (SELECT Senior AS name, sum(amount) deposit, count(amount) kol
            FROM deposits
            WHERE DAY(date) = DAY(NOW()) AND MONTH(date) = MONTH(NOW()) AND YEAR(date) = YEAR(NOW())
                  AND Junior = '' and type_dep!='CHBK'
            GROUP BY Senior
            UNION
            SELECT Senior AS name, round(sum(amount / 2), 2) deposit, count(amount) kol
            FROM deposits
            WHERE DAY(date) = DAY(NOW()) AND MONTH(date) = MONTH(NOW()) AND YEAR(date) = YEAR(NOW())
                  AND Junior !='' and type_dep!='CHBK'
            GROUP BY Senior
            UNION
            SELECT Junior AS name, round(sum(amount / 2), 2) deposit, count(amount) kol
            FROM deposits
            WHERE DAY(date) = DAY(NOW()) AND  MONTH(date) = MONTH(NOW()) AND YEAR(date) = YEAR(NOW())
                  AND Junior !='' and type_dep!='CHBK'
            GROUP BY Junior) dep
      GROUP BY name
      order by sum_dep desc limit 10;";
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
    $responce->rows[$i]['id']   = $row['name'];
    $responce->rows[$i]['cell'] = array( $row['name'],$row['kol_dep'],$row['sum_dep']);
}
echo json_encode($responce);



mysql_close($db);

?>