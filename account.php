<?php
if(!isset($_GET['manage'])) {
	return false;
	exit();
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Manage Account</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<style type="text/css">
		body {
			background-color: #eee;
			font-family: 'Roboto', sans-serif;
		}
		.container {
			background-color: rgba(150, 206, 180, 0.5);
			width: 100%;
			max-width: 550px;
			margin: 15px auto;
			margin-top: 50px;
			padding: 18px;
			border-radius: 10px;
			box-shadow: inset 5px 5px 6px rgba(255, 255, 255, 0.4), 
						inset -4px -4px 6px rgba(255, 255, 255, 0.4), 
						3px 3px 8px rgba(0, 0, 0, 0.4);
		}
		.controls {
			width: 100%;
			background-color: #eee;
			color: #777;
			border: thin solid #ccc;
			border-radius: 5px;
			padding: 10px 0px 10px 1px;
			margin: 0px 0px 8px 0px;
			font-weight: bolder;
			text-indent: 10px;
			/*outline: 0;*/
		}
		.controls:focus {
			border-color: #66afe9;
			outline: 0;
			-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(102, 175, 233, .6);
      				box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(102, 175, 233, .6);
		}
		#btn {
			background-color: #3BB9FF; /*98AFC7*/
			color: #fff;
			border-radius: 3px;
			padding: 12px 30px 12px 30px;
			border: thin solid #82CAFF; /*3BB9FF*/
			border-radius: 3px;
			font-family: sans-serif, helvetica;
			font-weight: bold;
			vertical-align: right;
			cursor: pointer; 
			/*left: 225px;*/
			/*position: relative;*/
		}
		#btn:hover {
			background-color: #96ceb4; /*#82CAFF;*/
			cursor: pointer;
		}
		#btn:active {
			background-color: #588c7e; /*#C2DFFF;*/
			color: #ccc; /*#777;*/
			outline: 0;
			cursor: pointer;
		}
		#btn:disabled {
			background-color: #C2DFFF;
			color: #ccc;
		}
		.close-item {
	    	/*position: relative;*/
	    	/*top: 5px;*/
	    	/*right: 10px;*/
	    	cursor: pointer;
	    	font-size: 25px;
	    	color: #fff;
	    	font-weight: bolder;
	    	float: right;
	    	margin-top: -15px;
	    	margin-right: -8px;
	    }
	    .close-item:hover {
	    	color: #eea29a;
	    }
	    .close-item:active {
	    	color: #c94c4c;
	    }
	    h2 {
	    	color: #fff; /*#588c7e;*/
	    	margin-top: -5px;
	    }
	    .control-label {
	    	color: #fff;
	    }
	    #progress {
			/*visibility: hidden;*/
			display: none;
			color: #fff;
			font-family: sans-serif;
			text-align: center;
			margin-top: 15px;
		}
		.errorMsg {
			color: #ea4335;
			font-weight: bolder;
		}
	</style>
	<script type="text/javascript">
		$('#oldPass').focus();
		function changePassword() {
			var oldpass = $('#oldPass').val().trim();
			var newpass = $('#newPass').val().trim();
			var confirmPass = $('#newPassConfirm').val().trim();

			if(oldpass == "" || oldpass == " ") {
				alert("Please type your old password!.");
				return false;
			}
			if(newpass == "" || newpass == " " && confirmPass == "" || confirmPass == " ") {
				alert("Please type your new password!.");
				return false;
			}
			if(newpass != confirmPass) {
				alert("Your new entered password does not match!");
				return false;
			}
			$('#progress').removeClass('errorMsg');
			$.ajax({
				type: "POST",
				url: "controller.php",
				data: {"changepass":"true", "oldpassword":oldpass, "newpassword":newpass},
				cache: false,
				beforeSend: function() {
					$('#progress').html('<center><span class="fa fa-spinner fa-pulse"></span> Processing...<center>').show();
				},
				success: function(data) {
					var jason = JSON.parse(data);
					$('#progress').html(jason.message).show();
					setTimeout(function() {
						$('#formWindow').removeClass('slideInDown').addClass('slideOutUp'); //remove
						$('.layer').delay(500).fadeOut('slow');
					}, 1000);
				},
				error: function() {
					$('#progress').addClass('animated flash').html("<center class='errorMsg'>Error 404: Url not found!</center>").show();
				}
			});
		}
		$('.close-item').click(function() {
			$('#formWindow').removeClass('slideInDown').addClass('slideOutUp'); //remove
			$('.layer').delay(500).fadeOut('slow'); 
		});
	</script>
</head>
<body>
	<div id="formWindow" class="container animated slideInDown">
		<span class="fa fa-times close-item" title="Close"></span>
		<h2><center>Update Password</center></h2>
		<form role="form" action="" method="" enctype="">
			<div class="item-control">
				<label class="control-label">Old Password:</label>
				<input type="text" class="controls" id="oldPass" name="old_password" placeholder="Enter your old password" autofocus autocomplete="off">
			</div>
			<div><hr></div>
			<div class="item-control">
				<label class="control-label">New Password:</label>
				<input type="password" class="controls" id="newPass" name="new_password" placeholder="Enter you new password" autocomplete="off">
			</div>
			<div class="item-control">
				<label class="control-label">Confirm New Password:</label>
				<input type="password" class="controls" id="newPassConfirm" name="new_password_confirm" placeholder="Enter you new password" autocomplete="off">
			</div>
			<div align="right" class="item-control">
				<button type="button" id="btn" onclick="changePassword();">Change Password</button>
			</div>
		</form>
		<div id="progress"></div>
		<br>
	</div>
</body>
</html>