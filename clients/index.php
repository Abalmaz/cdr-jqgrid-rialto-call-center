<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Clients</title>

    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <meta http-equiv="Content-Style-Type" content="text/css" />
   <link rel="stylesheet" type="text/css" media="screen" href="css/cupertino/jquery-ui.css" />

    <link rel="stylesheet" type="text/css" media="screen" href="css/ui.jqgrid.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="css/ui.jqgrid-bootstrap.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="css/ui.jqgrid-bootstrap-ui.css" />

    <script src="js/jquery-3.1.0.min.js" type="text/javascript"></script>
    <script src="js/i18n/grid.locale-en.js" type="text/javascript"></script>
    <script src="js/jquery.jqGrid.min.js" type="text/javascript"></script>
    <script src="css/cupertino/jquery-ui.js" type="text/javascript"></script>

    <script src="js/func.js" type="text/javascript"></script>

</head>
<body>
<?php
if ($_SESSION['user_id'] == "admin") {
    echo '<h1><center>Clients</center></h1>';
    echo '<div class="menu" align="center">
    <input value="Home" class="ui-button" onclick="location.href=\'/asterisk-stat/cdr.php\'" type="button" />
    <input value="Deposits" class="ui-button" onclick="location.href=\'/depos/index.php\'" type="button" />
    <input value="Retention" class="ui-button" onclick="location.href=\'/rtd/index.php\'" type="button" />
	<input value="Schedule" class="ui-button" onclick="location.href=\'/schedule/index.php\'" type="button" />
    <input value="Registration" class="ui-button" onclick="location.href=\'/site_reg/index.php\'" type="button" />
    <input value="Exit" class="ui-button" onclick="location.href=\'/index.php?clear=exit\'" type="button" />
    </div>
    <br>
'; }
else if ($_SESSION['user_id'] == "rtd") {
    echo '<h1><center>Clients</center></h1>';
    echo '<div class="menu" align="center">
    <input value="Audio Recording" class="ui-button" onclick="location.href=\'/ast-rec\'" type="button" />
    <input value="Deposits" class="ui-button" onclick="location.href=\'/depos/index.php\'" type="button" />
    <input value="Retention" class="ui-button" onclick="location.href=\'/rtd/index.php\'" type="button" />
	<input value="Schedule" class="ui-button" onclick="location.href=\'/schedule/index.php\'" type="button" />
  
    <input value="Exit" class="ui-button" onclick="location.href=\'/index.php?clear=exit\'" type="button" />
    </div>
    <br>
'; }
else {

    header("Location: /index.php");}
$_GET["page_num"]=$page_num;
?>
<style type="text/css">
   body{
        background: url(img/bg3.jpg) no-repeat center center fixed;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
        overflow: auto;
    }
    h1 {
        color: rgb(0,112,163);
        text-shadow: 0 1px 0 #ccc,
        0 2px 0 #c9c9c9,
        0 3px 0 #bbb,
        0 4px 0 #b9b9b9,
        0 5px 0 #aaa,
        0 6px 1px rgba(0,0,0,.1),
        0 0 5px rgba(0,0,0,.1),
        0 1px 3px rgba(0,0,0,.3),
        0 3px 5px rgba(0,0,0,.2),
        0 5px 10px rgba(0,0,0,.25),
        0 10px 10px rgba(0,0,0,.2),
        0 20px 20px rgba(0,0,0,.15);
        font-size: 250%;
    }
    .ui-jqgrid-view{
        box-shadow: 0 0 15px rgba(0,0,0,0.5);
    }
    .ui-button {
        box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);
    }
    .center .ui-jqgrid{  margin-left: auto; margin-right: auto; }

    /* set the size of the datepicker search control for Order Date*/
    #ui-datepicker-div { font-size:11px; }

    /* set the size of the autocomplete search control*/
    .ui-menu-item {
        font-size: 11px;
    }

    .ui-autocomplete {
        font-size: 11px;
    }

    .ui-jqgrid tr.ui-row-ltr td {
        font-size: 12px;

    }
    .ui-th-column th.ui-th-ltr{
        font-size: 12px;
    }
    .ui-jqgrid .ui-jqgrid-htable .ui-th-div  {
        height:auto;
        overflow:hidden;
        padding-right:2px;
        padding-top:2px;
        padding-left:2px;
        padding-bottom: 2px;
        position:relative;
        vertical-align:text-top;
        white-space:normal !important;
    }
    .ui-jqgrid-bdiv{
        height:580px !important;
    }
</style>
<br>
<br>
<div class="center">
<table id="grid" class="scroll"></table>
<div id="pager" class="scroll" style="text-align:center;"></div>
<form id="_export" method="post" action="export_excel.php">
    <input type="hidden" name="csvBuffer" id="csvBuffer" value="" />
 </div> 
</body>
</html>