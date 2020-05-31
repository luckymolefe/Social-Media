<?php
	if(!isset($_GET['getInvitations'])) {
		exit();
	}
	$email = (isset($_GET['email'])) ? $_GET['email'] : '';
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<style type="text/css">
		.container-item {
			max-width: 100%;
			max-height: 400px; 
			width: 400px;
			margin: 0 auto;
			background: #f5f5f5;
			border-radius: 5px;
			padding: 10px;
			margin-top: 30px;
			overflow-y: auto;
		}
		.invite-item {
			max-width: 100%;
			background-color: #96ceb4;
			color: #fff;
			font-weight: bolder;
			font-family: helvetica;
			font-size: 18px;
			line-height: 35px;
			text-indent: 10px;
			margin-bottom: 10px;
			height: 35px;
			border-radius: 0 5px 5px 0;
		}
		.invite-item-head {
			background-color: #8d9bd6;
			color: #fff;
			margin-left: -10px;
			margin-right: -10px;
			margin-bottom: 10px;
			font-size: 18px;
			padding: 6px;
		}
		.invite-item:hover {
			background-color: #daebe8;
			color: #b7b7b7;
			cursor: pointer;
		}
		.invite-item > img {
			max-width: 100%;
			width: 35px;
			margin-bottom: -5px;
			float: left;
		}
		.list-unstyled {
			list-style-type: none;
		}
		.close-item { 
			position: relative;
			top: -10px;
			left: -10px;
			z-index: 2;
			font-size: 20px;
			font-weight: bold;
			background-color: transparent;
			color: #777;
			padding: 2px 10px;
			cursor: pointer;
			border-radius: 5px 0 0 0;
		}
		.close-item:hover {
			background-color: #96ceb4; /*rgba(0, 0, 0, 0.6);*/
			color: #fff;
		}
	</style>
	<script type="text/javascript">
		$(function() {
			$('.close-item').click(function() {
				$('.container-item').removeClass('slideInDown').addClass('slideOutUp');
				$('.layer').delay(500).fadeOut('slow'); 
			});
		});
		function getInvitations() {
			$.get("controller.php", {"invitationlist":"true", "email":"<?php echo $email; ?>"}, function(data) {
				$('.invitationList').append('<li style="border-top:thin solid #eee;padding-top:5px;"><center>Checking invites...</center></li>');
				setTimeout(function() {
					$('.invitationList li:last').remove();
					$('.invitationList').html(data);
				}, 2000);
				invitesNotify(); //update popups after loading data immediately
			});
		}
		getInvitations();
	</script>
</head>
<body>
	<div class="container-item animated slideInDown">
	<span class="close-item" title="Close">&times;</span>
		<ul class="list-unstyled invitationList">
			<!-- <li class="invite-item"><img src="profile/avatars/avatar.png"> Lucky Molefe</li> -->
		</ul>
	</div>
</body>
</html>