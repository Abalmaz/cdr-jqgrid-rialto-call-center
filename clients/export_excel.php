<?php
$buffer = $_POST['csvBuffer'];

// file name for download
$filename = "clients_" . date('Ymd') . ".xls";

//header('Content-Encoding: UTF-8');
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Content-Type: application/vnd.ms-excel");
//mb_convert_encoding($buffer, 'UTF-16LE', 'UTF-8');
print chr(255) . chr(254) . mb_convert_encoding($buffer, 'UTF-16LE', 'UTF-8');

/*try{
    echo $buffer;
}catch(Exception $e){

}*/