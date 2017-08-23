<?php

require_once 'conf.php';

session_start();

// connect to the database
$db = mysql_connect($GLOBALS["db_host"], $GLOBALS["db_user"], $GLOBALS["db_pwd"])
or die("Connection Error: " . mysql_error());
mysql_select_db('asterisk') or die("Error connecting to db.");
mysql_set_charset('utf8');

    //читаем параметры
   // $page=$_GET['page'];
    //$limit = $_GET['rows']; // get how many rows we want to have into the grid
    //$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
    //$sord = $_GET['sord']; // get the direction if(!$sidx)

$page  = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;          // get the requested page
$limit = isset($_REQUEST['rows']) ? $_REQUEST['rows'] : 20;         // get how many rows we want to have into the grid
$sidx  = isset($_REQUEST['sidx']) ? $_REQUEST['sidx'] : 1;          // get index row - i.e. user click to sort
$sord  = isset($_REQUEST['sord']) ? $_REQUEST['sord'] : "asc";
$check = isset($_REQUEST['all']) ? $_REQUEST['all'] :"off";

//$m_mask=$_GET['m_mask'];
//$y_mask=$_GET['y_mask'];
//$all=$_GET['all'];
if ($check=='on')
{
    $period='';
}


else {
    if (isset($_GET['m_mask'])) $m_mask = $_GET['m_mask'];
    else $m_mask = 'MONTH(NOW())';
    if (isset($_GET['y_mask'])) $y_mask = $_GET['y_mask'];
    else $y_mask = 'YEAR(NOW())';
    $period="and MONTH(`date`) = $m_mask AND YEAR(`date`) = $y_mask";
}


$filterResultsJSON = json_decode($_REQUEST['filters']);

$filterArray = get_object_vars($filterResultsJSON);

$counter = 0;
$where='';
$ops = array(
    'eq'=>'=', //equal
    'ne'=>'<>',//not equal
    'lt'=>'<', //less than
    'le'=>'<=',//less than or equal
    'gt'=>'>', //greater than
    'ge'=>'>=',//greater than or equal
    'bw'=>'LIKE', //begins with
    'bn'=>'NOT LIKE', //doesn't begin with
    'in'=>'LIKE', //is in
    'ni'=>'NOT LIKE', //is not in
    'ew'=>'LIKE', //ends with
    'en'=>'NOT LIKE', //doesn't end with
    'cn'=>'LIKE', // contains
    'nc'=>'NOT LIKE'  //doesn't contain
);
function getWhereClause($col, $oper, $val){
    global $ops;
    if($oper == 'bw' || $oper == 'bn') $val .= '%';
    if($oper == 'ew' || $oper == 'en' ) $val = '%'.$val;
    if($oper == 'cn' || $oper == 'nc' || $oper == 'in' || $oper == 'ni') $val = '%'.$val.'%';
    return "$col {$ops[$oper]} '$val' ";
}
while($counter < count($filterArray['rules']))
{
    $filterRules = get_object_vars($filterArray['rules'][$counter]);
    $res = getWhereClause($filterRules['field'],$filterRules['op'], $filterRules['data']);
    $where .= ' AND '. $res;
    $counter++;
}


    $SQL = "SELECT deposits.id, substring(`date`,1,10) date_dep, type_dep, amount, Junior, Senior, clients.name,
    clients.platform, clients.id_platform, clients.lead_name, deposits.clients_id FROM deposits, clients
    where deposits.clients_id=clients.id $period $where
    ORDER BY $sidx $sord";
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


    $responce->page = $page;
    $responce->total = $total_pages;
    $responce->records = $count;
    //$i=0;
mysql_data_seek($result, $start);
$total=0;
for ($i = 0; $row = mysql_fetch_assoc($result); $i++) {
    if (($limit > 0) && ($i >= $limit)) break;
        $total += $row['amount'];
        $responce->rows[$i]['id']=$row[id];
        $responce->rows[$i]['cell']=array( $row[id_platform],$row[date_dep],$row[type_dep],$row[amount],$row[Junior],$row[Senior],
           $row[platform],$row[name],$row[lead_name], $row[clients_id]);
    }
$responce->userdata['amount'] = $total;
$responce->userdata['type_dep'] = 'Totals:';
    echo json_encode($responce);

mysql_close($db);
// end of getdata.php

?>