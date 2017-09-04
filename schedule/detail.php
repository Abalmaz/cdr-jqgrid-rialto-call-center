<?php
require_once 'conf.php';
session_start();
$id=$_GET['id'];

//// connect to the database
$db = mysql_connect($GLOBALS["db_host"], $GLOBALS["db_user"], $GLOBALS["db_pwd"])
or die("Connection Error: " . mysql_error());
mysql_select_db('asterisk') or die("Error connecting to db.");
mysql_set_charset('utf8');

$SQL="select clients.id, clients.mail, clients.country, clients.timezone,
       rtd.skype_name, dep.date_ftd, dep.amount_ftd,
       dep.date_rtd, dep.last_rtd, dep.sum_rtd, rtd.avg_income, rtd.field_activity, rtd.kind_activity
       from clients
join rtd on clients.id=rtd.clients_id
left join
       (SELECT DISTINCT deposits.clients_id,
                        substring(ftd.date_ftd,1,10) date_ftd,
                        ftd.amount_ftd,
                        substring(rtd.date_rtd,1,10) date_rtd,
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
        where clients.id=$id";
$result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());

//$result = $conn->query($sql);
$rows = array();
for ($i = 0; $row = mysql_fetch_assoc($result); $i++) {

    $rows[] = $row;
}
echo json_encode($rows);
//echo json_encode($responce);
