<?php
/*if(!isset($_GET['getplace'])) {
	return false;
	exit();
}*/

?>
<!-- <!DOCTYPE html>
<html>
<head> -->
	<title>Search Nearest Places</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- <link rel="stylesheet" type="text/css" href="../boostrap3/css/bootstrap.min.css"> -->
	<!-- <link rel="stylesheet" type="text/css" href="../boostrap3/font-awesome/css/font-awesome.min.css"> -->
	<!-- <script type="text/javascript" src="../boostrap3/jquery/jquery-1.12.4.min.js"></script> -->

	<!-- <script type="text/javascript" src="../boostrap3/jquery/jquery-3.1.0.min.js"></script> -->
	<!-- <script type="text/javascript" src="../boostrap3/jquery/typeahead.bundle.js"></script> -->
	<style type="text/css">
		.container-item {
			background: -webkit-radial-gradient(#b8a9c9, #5c4084);
			max-width: 100%;
			width: 500px;
			height: 300px;
			margin: 0 auto;
			padding: 15px;
			text-align: center;
			border-radius: 4px;
			margin-top: 150px;
		}
		.header {
			background: linear-gradient(#f5f5f5, #aaa);
			text-shadow: 1px 2px 3px rgba(0, 0, 0, 0.3);
			-webkit-background-clip: text;
			-webkit-text-fill-color: transparent;
			font-family: helvetica;
			font-size: 25px;
			font-weight: bold;
		}
		input {
			width: 400px;
			max-width: 100%;
			margin: 0 auto;
			border: thin solid #ccc;
			border-radius: 5px;
			padding: 10px;
			color: #777;
		}
		.autocomplete {
			position: relative;
			top: -2px;
			width: 400px;
			max-width: 100%;
			margin: 0 auto;
			background-color: rgba(200, 200, 200, 0.5); /*#f5f5f5;*/
			padding: 10px;
			border-radius: 0 0 4px 4px;
			border-left: thin solid #fff;
			border-right: thin solid #fff;
			border-bottom: thin solid #fff;
			max-height: 180px;
			overflow-y: auto;
			list-style-type: none;
			text-align: justify;
			display: none;
		}
		.autocomplete > li {
			background-color: #8d9db6;
			color: #fff;
			padding: 8px;
			margin-bottom: 5px;
			font-family: helvetica;
			margin-left: -10px;
			margin-right: -10px;
		}
		.autocomplete > li:hover {
			background-color: #bccad6;
			color: #777;
			cursor: pointer;
		}
		.loader {
			color: #fff;
		}
		.close-locator {
			position: relative;
			float: right;
			margin-top: -15px;
			background: linear-gradient(#f5f5f5, #aaa);
			text-shadow: 1px 2px 3px rgba(0, 0, 0, 0.3);
			-webkit-background-clip: text;
			-webkit-text-fill-color: transparent;
			font-weight: bold;
			cursor: pointer;
		}
		.close-locator:hover {
			text-shadow: 1px 2px 3px rgba(255, 255, 255, 0.3);
			-webkit-background-clip: text;
			-webkit-text-fill-color: #dd0000;
		}
	</style>
	<script type="text/javascript">
	$('#places').focus();
		$(document).ready(function() {

			/*$('#places').typeahead({
				source: function(query, result)
				{
					$.ajax({
						url: "placesnames.php",
						method: "POST",
						data: {query:query},
						dataType: "json",
						success: function(data) {
							$('.autocomplete').html(data).show();
							result($.map(data, function(item) {
								return item;
							}));
						}
					})
				}
			});*/

			$('#places').on('keyup', function() {
				var urldata = $('#places').val().trim();
				if(urldata == "") {
					return $('.autocomplete').hide();
				}
				$.ajax({
					type: "GET",
					url: "placesnames.php",
					data: {"getquery":"true","query":urldata},
					dataType: "json",
					cache: false,
					beforeSend: function() {
						$('.autocomplete').html("<center><span class='loader fa fa-spinner fa-pulse fa-2x'></span></center>").show();
					},
					success: function(data) {
						$('.autocomplete').html(data).show();
					}
				});
			});
		});

		function actionSelect(element) {
			var placeSelected = element.dataset.placename;
			$('#places').val(placeSelected);
			$('.autocomplete').fadeOut('fast');
			$('#checkinPlace').val(placeSelected); //testing for socialmedia, place selected item value in checkin-box
			$('.layer').fadeOut('slow'); //testing for socialmedia, close layer after selection
			$('#shareCheckin').attr('disabled', false); //enable checkin button after selection
		}
		$('.close-locator').click(function() {
			$('.layer').fadeOut('fast'); //testing for socialmedia
		});
	</script>
<!-- </head> -->
<!-- <body> -->
	<div class="container-item">
		<span class="fa fa-time fa-3x close-locator" title="Close">&times;</span>
		<h1 class="header">Search The Nearest Place</h1>
		<input type="text" name="places" id="places" autocomplete="off" placeholder="Type name of place...">
		<ul class="autocomplete"></ul>
	</div>
<!-- </body> -->
<!-- </html> -->