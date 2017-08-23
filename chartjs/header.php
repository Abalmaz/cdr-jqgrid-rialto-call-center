<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		
		<title>Top traders</title>
		
		<!-- stylesheets -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/font-awesome/css/font-awesome.min.css" rel="stylesheet">
		<link href="css/style.css" rel="stylesheet">
		
      	<link rel="stylesheet" type="text/css" media="screen" href="css/cupertino/jquery-ui.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="css/ui.jqgrid.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="css/ui.jqgrid-bootstrap.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="css/ui.jqgrid-bootstrap-ui.css" />
      
		
		<!-- scripts -->
		<script src="js/jquery-3.1.0.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script language="javascript" type="text/javascript" src="js/chart.js"></script>
		<script language="javascript" type="text/javascript" src="js/app.js"></script>
      
        <script src="js/i18n/grid.locale-en.js" type="text/javascript"></script>
		<script src="js/jquery.jqGrid.min.js" type="text/javascript"></script>
		<script src="css/cupertino/jquery-ui.js" type="text/javascript"></script>
		<script src="js/func.js" type="text/javascript"></script>

		
		<script>
			$(function () {
				// #sidebar-toggle-button
				$('#sidebar-toggle-button').on('click', function () {
						$('#sidebar').toggleClass('sidebar-toggle');
						$('#page-content-wrapper').toggleClass('page-content-toggle');	
						fireResize();					
				});
				
				// sidebar collapse behavior
				$('#sidebar').on('show.bs.collapse', function () {
					$('#sidebar').find('.collapse.in').collapse('hide');
				});
				
				// To make current link active
				var pageURL = $(location).attr('href');
				var URLSplits = pageURL.split('/');

				//console.log(pageURL + "; " + URLSplits.length);
				//$(".sub-menu .collapse .in").removeClass("in");

				if (URLSplits.length === 5) {
					var routeURL = '/' + URLSplits[URLSplits.length - 2] + '/' + URLSplits[URLSplits.length - 1];
					var activeNestedList = $('.sub-menu > li > a[href="' + routeURL + '"]').parent();

					if (activeNestedList.length !== 0 && !activeNestedList.hasClass('active')) {
						$('.sub-menu > li').removeClass('active');
						activeNestedList.addClass('active');
						activeNestedList.parent().addClass("in");
					}
				}

				function fireResize() {
					if (document.createEvent) { // W3C
						var ev = document.createEvent('Event');
						ev.initEvent('resize', true, true);
						window.dispatchEvent(ev);
					}
					else { // IE
						element = document.documentElement;
						var event = document.createEventObject();
						element.fireEvent("onresize", event);
					}
            	}
			})
		</script>
		
	</head>
	
	<body>
		<!-- header -->
		<nav id="header" class="navbar navbar-fixed-top">
			<div class="container-fluid">
				<div class="navbar-header">
					<div id="sidebar-toggle-button">
						<i class="fa fa-bars" aria-hidden="true"></i>
					</div>
					<div class="brand">
						<a href="/">
							CDR Asterisk <span class="hidden-xs text-muted">DarTrader</span>
						</a>
					</div>
					
				</div>
			</div>
		</nav> 
		<!-- /header -->         