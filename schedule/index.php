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
    <!--<script src="https://code.jquery.com/jquery-1.12.4.js"></script>-->

    <script src="js/func_user.js" type="text/javascript"></script>
    <script src="js/func_admin.js" type="text/javascript"></script>


</head>
<body>
<style>
    .pq-grid div.pq-tabs
    {
        width:650px;
    }
    .pq-grid div.pq-tabs *
    {
        white-space:normal;
    }
    .pq-grid p
    {
        margin:10px;
    }
    .pq-grid .pq-tabs b
    {
        font-weight:bold;
    }

    /* set the size of the datepicker search control for Order Date*/
    #ui-datepicker-div { font-size:12px; }

    /* set the size of the autocomplete search control*/
    .ui-menu-item {
        font-size: 12px;
    }
    body{
       // background: url(img/bg3.jpg) no-repeat center center fixed;
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

    .ui-autocomplete {
        font-size: 12px;
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
        height:auto !important;
    }

</style>
<?php if ($_SESSION['user_id'] == "admin" ) {
    echo '
    <h1><center>Retention</center></h1>';
    echo '
    <div class="menu" align="center">
    <input value="Home" class="ui-button" onclick="location.href=\'/asterisk-stat/cdr.php\'" type="button" />
    <input value="Deposits" class="ui-button" onclick="location.href=\'/depos/index.php\'" type="button" />
    <input value="Clients" class="ui-button" onclick="location.href=\'/clients/index.php\'" type="button" />
    <input value="Registration" class="ui-button" onclick="location.href=\'/site_reg/index.php\'" type="button" />
    <input value="Exit" class="ui-button" onclick="location.href=\'/index.php?clear=exit\'" type="button" />
    </div>
    <br>
    <br>
    <table id="grid_admin" class="scroll"></table>
    <script type="text/template" id="tmpl">
    <div class="tabs" id = "tabs">
        <ul>
            <li><a href="#tabs-1">About</a></li>
            <li><a href="#tabs-2">Deposits</a></li>            
        </ul>
        <div id="tabs-1">
        <table>
            <tr>
                <td><b>Email:  </b><#=mail#></td>
                <td><b>Everage income:  </b><#=avg_income#></td>
            </tr>
            <tr>
                <td><b>Skype account:  </b><#=skype_name#></td>
                <td><b>Field of activity:  </b><#=field_activity#></td>
            </tr>
             <tr>
                <td><b>Country:  </b> <#=country#> </td>
                <td><b>Kind of activity:  </b><#=kind_activity#></td>
            </tr> 
            <tr>
                <td><b>Time zone:  </b> <#=timezone#> </td>
            </tr>
        </table>
        </div>
        <div id="tabs-2">
            <p><b>Date of FTD:  </b><#=date_ftd#></p>
            <p><b>FTD:  </b><#=amount_ftd#></p>
            <p><b>Date last RTD:  </b><#=date_rtd#></p>
            <p><b>Last RTD:  </b><#=last_rtd#></p>
            <p><b>Total RTD:  </b><#=sum_rtd#></p>
        </div>
    </div>
    </script>
    <div id="pager_admin" class="scroll" style="text-align:center;"></div>
    <form id="_export" method="post" action="export_excel.php">
    <input type="hidden" name="csvBuffer" id="csvBuffer" value="" />
'; }
elseif ($_SESSION['user_id'] == "user"){
    echo '<h1><center>Retention</center></h1>';
    echo '
<div class="menu" align="center">
<input value="Home" class="ui-button" onclick="location.href=\'/asterisk-stat/cdr.php\'" type="button" />
<input value="Audio Recording" class="ui-button" onclick="location.href=\'/ast-rec/index.php\'" type="button" />
<input value="Statistics registration" class="ui-button" onclick="location.href=\'/phone/stat.php\'" type="button" />
<input value="Exit" class="ui-button" onclick="location.href=\'/index.php?clear=exit\'" type="button" />
</div>
<br>
    <br>
    <table id="grid_user" class="scroll"></table>
   <script type="text/template" id="tmpl">
    <div class="tabs" id = "tabs">
        <ul>
            <li><a href="#tabs-1">About</a></li>
            <li><a href="#tabs-2">Deposits</a></li>            
        </ul>
        <div id="tabs-1">
        <table>
            <tr>
                <td><b>Email:  </b><#=mail#></td>
                <td><b>Everage income:  </b><#=avg_income#></td>
            </tr>
            <tr>
                <td><b>Skype account:  </b><#=skype_name#></td>
                <td><b>Field of activity:  </b><#=field_activity#></td>
            </tr>
             <tr>
                <td><b>Country:  </b> <#=country#> </td>
                <td><b>Kind of activity:  </b><#=kind_activity#></td>
            </tr> 
            <tr>
                <td><b>Time zone:  </b> <#=timezone#> </td>
            </tr>
        </table>
        </div>
        <div id="tabs-2">
            <p><b>Date of FTD:  </b><#=date_ftd#></p>
            <p><b>FTD:  </b><#=amount_ftd#></p>
            <p><b>Date last RTD:  </b><#=date_rtd#></p>
            <p><b>Last RTD:  </b><#=last_rtd#></p>
            <p><b>Total RTD:  </b><#=sum_rtd#></p>
        </div>
    </div>
    </script>
    <div id="pager_user" class="scroll" style="text-align:center;"></div>
';
}
elseif ($_SESSION['user_id'] == "rtd"){
    echo '<h1>Welcome</h1>';
    echo '
<div class="menu" align="center">
<input value="Audio Recording" class="ui-button" onclick="location.href=\'/ast-rec/index.php\'" type="button" />
<input value="Deposits" class="ui-button" onclick="location.href=\'/depos/index.php\'" type="button" />
<input value="Clients" class="ui-button" onclick="location.href=\'/clients/index.php\'" type="button" />
<input value="Exit" class="ui-button" onclick="location.href=\'/index.php?clear=exit\'" type="button" />
</div>
<br>
    <br>
    <br>
    <table id="grid" class="scroll"></table>
    <div id="pager" class="scroll" style="text-align:center;"></div>
  
';
}
else {

    header("Location: /index.php");
}
//$_GET["page_num"]=$page_num;
?>

</body>
</html>