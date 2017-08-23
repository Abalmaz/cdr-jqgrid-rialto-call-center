<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>
<?php include 'content.php'; ?>
<style>
.ui-jqgrid tr.jqgrow td {font-size:1.2em;
			font-weight: bold !important;}
        .ui-th-column th.ui-th-ltr{
            font-size: 1.5em !important;
        }
        .green {
            background-color: #e8f8f5;
            color: #2779aa;
        }
    </style>
    <!-- <div id="chart-container">
        <canvas id="mycanvas"></canvas>
    </div>
<br> -->
 <!-- <div class="top_dep"> -->
        <div class="row">
        <div  class="col-xs-6">
            <label for="month" style="margin-left: 140px; font-size: 20px; color: #2779aa;">Current month </label>
            <table id="month_type"></table>
            <div id="pager_type_m" class="scroll"></div>
            <table id="month"></table>
            <div id="pager_m" class="scroll"></div>
            </div>
                <div  class="col-xs-6" style="padding-left: 80px">
                    <label for="day" style="margin-left: 115px; font-size: 20px; color: #2779aa;">Current day </label>
                    <table id="day_type"></table>
                    <div id="pager_type_d" class="scroll"></div>
                    <table id="day"></table>
                    <div id="pager_d" class="scroll"></div>
                    </div>
        </div>
<!-- </div> -->

<?php include 'footer.php'; ?>