<?php
require_once 'conf.php';

$today = date('Y-m-d');

session_start();
//$team=$_SESSION['team'];
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
$status = isset($_GET['status']) ? $_GET['status'] : '';

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
$filter = '';
if ($status !='')
{
    $filter = " and status=$status";
}
$SQL="select clients.id, clients.id_platform, clients.platform, clients.name,
       clients.secret_phone, substring(schedule.date_call,1,10) date_call,  users.name trader,
       CASE 
       WHEN task like 'call' then 'позвонить'
       when task like 'trade' then 'торговать'
       when task like 'cancel_wd' then 'отмена вывода'
        end as task,
       CASE 
       WHEN result like 'not done' then 'не выполнено'
       when result like 'done' then 'выполнено'
       when result like 'in process' then 'в процессе'
        end as result,
        clients.note, clients.team, 
        clients.phone
       from clients       
        left join schedule on clients.id=schedule.clients_id
        left join users on clients.team = users.team
        $where $filter
        ORDER BY  $sidx $sord";
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
    if ( substr($row[phone],0,3)==380) {
        $dst=substr($row[phone],2);
        $last_data = mysql_query("SELECT calldate FROM cdr WHERE dst=$dst AND disposition = 'ANSWERED' ORDER BY id DESC LIMIT 1");
    }
    else {
        $last_data = mysql_query("SELECT calldate FROM cdr WHERE dst=$row[phone] AND disposition = 'ANSWERED' ORDER BY id DESC LIMIT 1");

    }
    $last = mysql_fetch_assoc($last_data);
    $responce->rows[$i]['id']   = $row['id'];
    $responce->rows[$i]['cell'] = array( $row[id_platform],$row[platform],$row[name],$row[secret_phone],$last[calldate],
        $row[date_call], $row[trader], $row[task], $row[result], $row[note], $row[team]);
}
echo json_encode($responce);

mysqli_close($db);

?>