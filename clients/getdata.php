<?php
require_once 'conf.php';

session_start();

//// connect to the database
$db = mysql_connect($GLOBALS["db_host"], $GLOBALS["db_user"], $GLOBALS["db_pwd"])
or die("Connection Error: " . mysql_error());
mysql_select_db('asterisk') or die("Error connecting to db.");
mysql_set_charset('utf8');

//read parameters
$page  = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;          // get the requested page
$limit = isset($_REQUEST['rows']) ? $_REQUEST['rows'] : 20;         // get how many rows we want to have into the grid
$sidx  = isset($_REQUEST['sidx']) ? $_REQUEST['sidx'] : 1;          // get index row - i.e. user click to sort
$sord  = isset($_REQUEST['sord']) ? $_REQUEST['sord'] : "asc";      // get the direction
//if(!$sidx) $sidx =1;


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
    if($counter == 0){
        $where .= ' WHERE '. $res;
    }
    else {
        $where .= ' AND ' . $res;
    }
    $counter++;
}
    $SQL="select id, platform, id_platform, name, phone, second_phone, secret_phone,  mail, birthday, country, 
    CASE
    WHEN clients.team=1 then 'Askerova'
    WHEN clients.team=2 then 'Mazurok'
    WHEN clients.team=3 then 'Vinnichenko'
    WHEN clients.team=4 then 'Ostrovaya'
    WHEN clients.team=666 then 'кладбище'
    end as team, 
        CASE 
       WHEN clients.status=1 then 'торгует'
       when clients.status=2 then 'не торгует'
       when clients.status=4 then 'еще не торговал'
       when clients.status=3 then 'мертв'
end as status, lead_name, documents
          FROM clients ".$where." ORDER BY  $sidx $sord";
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
    $responce->rows[$i]['id']   = $row['id'];
    $responce->rows[$i]['cell'] = array($row['id_platform'], $row['platform'],$row['name'],$row['phone'],$row['second_phone'],$row['secret_phone'],$row['mail'],
        $row['birthday'],$row['country'],$row['team'],$row['status'], $row['lead_name'], $row['documents']);
}
echo json_encode($responce);

mysql_close($db);

?>