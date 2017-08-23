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

    $SQL="select  uid, name, phone, email, comments, 
          language, date_reg, date_modify, status,
          CASE
          WHEN id_transaction is not null and id_transaction!='' THEN 'affiliate'
          END AS id_transaction
          from site_registration
         ".$where."
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
        $last_data = mysql_query("SELECT calldate as last_call FROM cdr WHERE dst=$dst and cdr.calldate >= STR_TO_DATE('$row[date_reg] 00:00:00', '%Y-%m-%d %H:%i:%s') ORDER BY id DESC LIMIT 1;");
        $fail_call = mysql_query("SELECT count(cdr.disposition) count_fail FROM cdr WHERE cdr.disposition != 'ANSWERED' and cdr.calldate >= STR_TO_DATE('$row[date_reg]', '%Y-%m-%d %H:%i:%s') AND cdr.dst=$dst;");
    }
    else {
        $last_data = mysql_query("SELECT calldate  as last_call FROM cdr WHERE dst=$row[phone] and cdr.calldate >= STR_TO_DATE('$row[date_reg]', '%Y-%m-%d %H:%i:%s') ORDER BY id DESC LIMIT 1;");
        $fail_call = mysql_query("SELECT count(cdr.disposition) count_fail FROM cdr WHERE cdr.disposition != 'ANSWERED' and cdr.calldate >= STR_TO_DATE('$row[date_reg]', '%Y-%m-%d %H:%i:%s') AND cdr.dst=$row[phone];");
    }
    $fail = mysql_fetch_assoc($fail_call);
    $last = mysql_fetch_assoc($last_data);
    $responce->rows[$i]['id']   = $row['uid'];
    $responce->rows[$i]['cell'] = array($row[uid], $row[name],$row[phone],$row[email],$row[comments],$row[date_reg],$row[language], $row[status], $last[last_call], $fail[count_fail],$row[id_transaction]);
}
echo json_encode($responce);

mysqli_close($db);

?>