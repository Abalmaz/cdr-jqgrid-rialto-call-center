<?php
$buffer = $_POST['csvBuffer'];

// file name for download
$filename = "deposits_" . date('Ymd') . ".xls";

header("Content-Disposition: attachment; filename=\"$filename\"");
header("Content-Type: application/vnd.ms-excel");
print chr(255) . chr(254) . mb_convert_encoding($buffer, 'UTF-16LE', 'UTF-8');

