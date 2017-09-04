<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Deposits</title>

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
<style>
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
  .search {
  		color: rgb(0,112,163);
  }
    .ui-jqgrid-view{
        box-shadow: 0 0 15px rgba(0,0,0,0.5);
    }
    .ui-button {
        box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);
    }
    .ui-jqgrid{  margin-left: auto; margin-right: auto; }
    .ui-jqgrid tr.ui-row-ltr td {
        font-size: 1.2em;

    }
    .ui-th-column th.ui-th-ltr{
        font-size: 1.2em;
    }
    .ui-jqgrid-bdiv {
        height: auto !important;
    }
    .ui-front {
        z-index: 1234;
    }
    </style>
</head>
<body>
<?php if ($_SESSION['user_id'] == "admin") {
echo '<h1><center>Deposits</center></h1>';
echo '<div class="menu" align="center">
<input value="Home" class="ui-button" onclick="location.href=\'/asterisk-stat/cdr.php\'" type="button" />
<input value="Clients" class="ui-button" onclick="location.href=\'/clients/index.php\'" type="button" />
<input value="Retention" class="ui-button" onclick="location.href=\'/rtd/index.php\'" type="button"/>
<input value="Schedule" class="ui-button" onclick="location.href=\'/schedule/index.php\'" type="button" />
<input value="Registration" class="ui-button" onclick="location.href=\'/site_reg/index.php\'" type="button" />
<input value="Exit" class="ui-button" onclick="location.href=\'/index.php?clear=exit\'" type="button" />
</div>
<br>
<br>';
}
else if ($_SESSION['user_id'] == "rtd") {
echo '<h1><center>Deposits</center></h1>';
echo '<div class="menu" align="center">
<input value="Audio Recording" class="ui-button" onclick="location.href=\'/ast-rec\'" type="button" />
<input value="Clients" class="ui-button" onclick="location.href=\'/clients/index.php\'" type="button" />
<input value="Retention" class="ui-button" onclick="location.href=\'/rtd/index.php\'" type="button"/>
<input value="Schedule" class="ui-button" onclick="location.href=\'/schedule/index.php\'" type="button" />
<input value="Exit" class="ui-button" onclick="location.href=\'/index.php?clear=exit\'" type="button" />
</div>
<br>
<br>';
}

else {

header("Location: /index.php");
}

?>
 <div class = "search" align="center"> 
<label for="month"><b>Select a month:<b>  </label>
<select name="month" id="month" onchange="doSearch(arguments[0]||event)">
         <option disabled selected>Select month</option>
         <option value="01">January</option>
         <option value="02">February</option>
         <option value="03">March</option>
         <option value="04">April</option>
         <option value="05">May</option>
         <option value="06">June</option>
         <option value="07">July</option>
         <option value="08">August</option>
         <option value="09">September</option>
         <option value="10">October</option>
         <option value="11">November</option>
         <option value="12">December</option>
</select>
    <label for="year" style="margin-left:15px;"><b>Select a year: <b> </label>
<select name="year" id="year" onchange="doSearch(arguments[0]||event)">
        <option disabled selected>Select year</option>
        <option value="2017">2017</option>
        <option value="2016">2016</option>
        <option value="2015">2015</option>
        <
</select>
  <label for="'all" style="margin-left:15px;"><b>All periods</label>
<input type="checkbox" name="all" id="all" class="ui-checkboxradio-radio-label">
<button onclick="gridReload()" id="submitButton" style="margin-left:15px;" class="ui-button" >Apply</button>
 </div>   
 <br>
<table id="grid" class="scroll"></table>
<div id="pager" class="scroll" style="text-align:center;"></div>
  <form id="_export" method="post" action="export_excel.php">
    <input type="hidden" name="csvBuffer" id="csvBuffer" value="" /> 
</body>
</html>