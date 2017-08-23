<?php
header('Content-Type: application/json');
session_start();
$sip=$_SESSION['sip'];
$dst = $_GET['phone'];


$timeout = 10;
$asterisk_ip = "192.168.1.254";

$socket = fsockopen($asterisk_ip,"5038", $errno, $errstr, $timeout);
fputs($socket, "Action: Login\r\n");
fputs($socket, "UserName: onclick\r\n");
fputs($socket, "Secret: IJN543uhb098\r\n\r\n");

$wrets=fgets($socket,128);

//echo $wrets;

fputs($socket, "Action: Originate\r\n" );
fputs($socket, "Channel: SIP/$sip\r\n" );
fputs($socket, "Exten: $dst\r\n" );
fputs($socket, "Callerid: $sip\r\n" );

fputs($socket, "Context: plan\r\n" );
fputs($socket, "Priority: 1\r\n" );
fputs($socket, "Async: yes\r\n\r\n" );

$wrets=fgets($socket,128);
//echo $wrets;