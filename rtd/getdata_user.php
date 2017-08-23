<?php
require_once 'conf.php';

session_start();
$team=$_SESSION['team'];
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
    $where .= ' AND '. $res;
    $counter++;
}
$filter = '';
if ($status !='')
{	
		$filter = " and status=$status";	
}

    $SQL="select clients.id, clients.id_platform, clients.platform, clients.name, clients.birthday,
       clients.secret_phone, clients.mail,
       CASE 
       WHEN clients.status=1 then 'торгует'
       when clients.status=2 then 'не торгует'
       when clients.status=4 then 'еще не торговал'
       when clients.status=3 then 'мертв'
end as status,
       rtd.skype_date, rtd.skype_name, substring(rtd.date_contact,1,10) date_contact, dep.date_ftd, dep.amount_ftd,
       dep.date_rtd, dep.last_rtd, dep.sum_rtd, rtd.note,  rtd.avg_income, rtd.field_activity, rtd.kind_activity, clients.documents, clients.phone
       from clients
join rtd on clients.id=rtd.clients_id
left join
       (SELECT DISTINCT deposits.clients_id,
                        ftd.date_ftd,
                        ftd.amount_ftd,
                        rtd.date_rtd,
                        rtd.last_rtd,
                        rtd_sum.sum_rtd
        FROM deposits left join
             (SELECT clients_id, date date_ftd, sum(amount) amount_ftd
              FROM deposits
              WHERE deposits.type_dep = 'ftd'
              group by deposits.clients_id) ftd on deposits.clients_id = ftd.clients_id
              left join (select clients_id, sum(amount) sum_rtd from deposits where deposits.type_dep = 'rtd' 
              GROUP BY deposits.clients_id) rtd_sum on rtd_sum.clients_id=deposits.clients_id
            left join (SELECT dep.clients_id, dep.date date_rtd, sum(dep.amount) last_rtd
                      FROM deposits dep inner join 
                      (select deposits.clients_id, max(date) date from deposits where deposits.type_dep = 'rtd' 
                      group by deposits.clients_id) max_date on 
                      max_date.clients_id=dep.clients_id and dep.date=max_date.date
                      group by dep.clients_id
              )rtd on deposits.clients_id = rtd.clients_id
            GROUP BY deposits.clients_id)
        dep on clients.id=dep.clients_id
        where clients.team=$team $where $filter
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
    $responce->rows[$i]['cell'] = array( $row[id_platform],$row[platform],$row[name],$row[birthday],$row[secret_phone],$row[mail],$row[status],$last[calldate],
        $row[skype_date], $row[skype_name],$row[date_contact],
        $row[date_ftd],$row[amount_ftd], $row[date_rtd],$row[last_rtd], $row[sum_rtd], $row[note], $row[avg_income], $row[field_activity], $row[kind_activity], $row[documents]);
}
echo json_encode($responce);

mysqli_close($db);

?>