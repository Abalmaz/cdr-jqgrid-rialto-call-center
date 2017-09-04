<?php
session_start();
$team=$_SESSION['team'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Retention</title>

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
    <script src="js/func_user.js" type="text/javascript"></script>


</head>
<body>
<style type="text/css">

    /* set the size of the datepicker search control for Order Date*/
    #ui-datepicker-div { font-size:12px; }

    /* set the size of the autocomplete search control*/
    .ui-menu-item {
        font-size: 12px;
    }

    .ui-autocomplete {
        font-size: 12px;
    }

    .ui-jqgrid tr.ui-row-ltr td {
        font-size: 1.2em;

    }
    .ui-th-column th.ui-th-ltr{
        font-size: 1em;
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
    .ui-jqgrid tr.jqgrow td {
        white-space: normal !important;
    }
    .ui-jqgrid-bdiv{
        height:600px !important;
    }
</style>
<?php if ($_SESSION['user_id'] == "admin" ) {
    echo '<h1><center>Retention</center></h1>';
    echo '<input value="Home" onclick="location.href=\'/asterisk-stat/cdr.php\'" type="button" />
    <input value="Deposits" onclick="location.href=\'/depos/index.php\'" type="button" />
    <input value="Clients" onclick="location.href=\'/clients/index.php\'" type="button" />
	<input value="Schedule" onclick="location.href=\'/schedule/index.php\'" type="button" />
    <input value="Registration" onclick="location.href=\'/site_reg/index.php\'" type="button" />
    <input value="Exit" onclick="location.href=\'/index.php?clear=exit\'" type="button" />
    <br>
    <br>
    <center><button onclick="gridReloadA()" id="filter_adm">Не торгуют</button></center>
    <br>
    <br>
    <table id="grid" class="scroll"></table>
    <div id="pager" class="scroll" style="text-align:center;"></div>
    <form id="_export" method="post" action="export_excel.php">
    <input type="hidden" name="csvBuffer" id="csvBuffer" value="" />
'; }
elseif ($_SESSION['user_id'] == "user"){ 
echo '<h1>Hi, User</h1>';
echo '<input value="Home" onclick="location.href=\'/asterisk-stat/cdr.php\'" type="button" />
<input value="Schedule" onclick="location.href=\'/schedule/index.php\'" type="button" />
<input value="Audio Recording" onclick="location.href=\'/ast-rec/index.php\'" type="button" />
<input value="Statistics registration" onclick="location.href=\'/phone/stat.php\'" type="button" />
<input value="Exit" onclick="location.href=\'/index.php?clear=exit\'" type="button" />
<br>
    <br>
    <center><button onclick="gridReload()" id="filter">Не торгуют</button></center>
    <br>
    <br>
    <table id="grid_user" class="scroll"></table>
    <div id="pager_user" class="scroll" style="text-align:center;"></div>
   <!-- <form id="_export" method="post" action="export_excel.php">
    <input type="hidden" name="csvBuffer" id="csvBuffer" value="" /> -->
';
}
elseif ($_SESSION['user_id'] == "rtd"){ 
echo '<h1>Welcome</h1>';
echo '
<input value="Audio Recording" onclick="location.href=\'/ast-rec/index.php\'" type="button" />
<input value="Deposits" onclick="location.href=\'/depos/index.php\'" type="button" />
<input value="Clients" onclick="location.href=\'/clients/index.php\'" type="button" />
<input value="Schedule" onclick="location.href=\'/schedule/index.php\'" type="button" />
<input value="Exit" onclick="location.href=\'/index.php?clear=exit\'" type="button" />
<br>
    <br>
    <br>
    <table id="grid" class="scroll"></table>
    <div id="pager" class="scroll" style="text-align:center;"></div>
   <!-- <form id="_export" method="post" action="export_excel.php">
    <input type="hidden" name="csvBuffer" id="csvBuffer" value="" /> -->
';
}
else {

    header("Location: /index.php");
}
//$_GET["page_num"]=$page_num;
?>

</body>
</html>