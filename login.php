<!-- <?php ?> -->
<!DOCTYPE html>
<html>
<head>
	<title>Social | Login</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- <link rel="stylesheet" type="text/css" href="../boostrap3/css/bootstrap.min.css"> -->
	<link rel="stylesheet" type="text/css" href="../boostrap3/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="styles/animate.css">

	<script type="text/javascript" src="../boostrap3/jquery/jquery-1.12.4.min.js"></script>
	<!-- <script type="text/javascript" src="../boostrap3/js/bootstrap.min.js"></script> -->
	<style type="text/css">
		body {
			background: -webkit-linear-gradient(90deg, #d9ecd0 10%, #96ceb4 70%); /*-webkit-linear-gradient(90deg, #0F7DC2 10%, #034f84 70%);*/
			background-repeat: no-repeat;
			background-size: 100% 990px;
			max-width: 100%;
			margin: 0 auto;
		}
		.login-form {
			max-width: 400px;
			margin: 0 auto;
			background-color: rgba(245, 245, 245, 0.6); /*#f5f5f5;*/
			padding: 50px 40px;
			border-radius: 5px;
			margin-top: 50px;
			box-shadow: 2px 1px 5px rgba(0, 0, 0, 0.5);
		}
		.form-control > .form-input {
			width: 100%;
			margin-top: 5px;
			margin-bottom: 12px;
			padding-top: 10px;
			padding-bottom: 10px;
			text-indent: 38px;
			font-family: 'Segoe UI', helvetica, arial;
			font-size: 18px;
			background-color: #f0f0f0; /*#e0e2e4;*/
			border: thin inset #999;
			border-radius: 2px;
			color: #034f84;
			outline: 0;
		}
		input[type="email"] {
			background: url('backgrounds/typicons/user-outline.png');
			background-repeat: no-repeat;
			background-size: 40px;
		}
		input[type="password"] {
			background: url('backgrounds/typicons/lock-closed-outline.png');
			background-repeat: no-repeat;
			background-size: 40px;
		}
		
		.form-control > input[type="email"]:focus, input[type="password"]:focus {
			border-color: #66afe9;
			-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(102, 175, 233, .6);
		}
		label {
			font-family: helvetica, arial;
		}
		.heading {
			position: relative;
			left: 0px;
			top: 70px;
			font-family: 'Bauhaus 93', helvetica, arial;
			font-size: 3em;
			text-align: center;
			color: #f5f5f5;
			/*box-shadow: 1px 1px 5px rgba(0, 0, 0, 0.4); */
			background: -webkit-linear-gradient(-90deg, #d9ecd0 20%, #96ceb4 70%);/*first==>-webkit-linear-gradient(90deg, #888 50%, #c0c0c0 ); last==>linear-gradient(-90deg, #0F7DC2 20%, #034f84 70%)*/
			max-width: 478px; /*480px*/
			margin: 0 auto;
			border-radius: 5px 5px 0 0;
			border-top: thin solid #f5f5f5;
			border-left: thin solid #f5f5f5;
			border-right: thin solid #f5f5f5;
			box-shadow: inset 0px 3px 4px rgba(255, 255, 255, 0.5), 2px -2px 4px rgba(0, 0, 0, 0.3);
			text-shadow: 2px -0px 6px rgba(0, 0, 0, 0.6);
		}
		.btn-login {
			width: 120px;
			background: -webkit-linear-gradient(-90deg, #96ceb4 0%, #d9ecd0 70%);
			/*background-color: #d9ecd0; /*#96ceb4;;*/
			color: #555;
			border: thin solid #96ceb4; /*#c0c0c0;*/
			box-shadow: 1px 1px 5px rgba(0, 0, 0, 0.3);
			border-radius: 2px;
			padding: 10px;
			margin-top: 20px;
			font-weight: bolder;
		}
		.btn-login:hover {
			background-color: #e0e2e4;
			color: #999;
			box-shadow: 1px 1px 5px rgba(0, 0, 0, 0.3);
			cursor: pointer;
		}
		.btn-login:active {
			background: -webkit-linear-gradient(90deg, #96ceb4 0%, #d9ecd0 70%);
			/*background-color: #96ceb4; /*#d9ecd0;*/
			color: #888;
			border-color: #c0c0c0; /*#96ceb4;*/
			outline: 0;
		}
		.btn-login:focus {
			outline: 0;
		}
		.remember {
			font-family: helvetica, arial;
			color: #555;
		}
		.link > a {
			position: relative;
			top: -25px;
			font-family: helvetica, arial;
			text-decoration: none;
			color: #0F7DC2;
			float: right;
		}
		.link > a:hover {
			/*text-decoration: underline;*/
			border-bottom: thin solid #96ceb4;
		}
		@media screen and (max-width: 360px) {
			.link > a {
				top: 20px;
				left: -50px;
			}
		}
		.nav-link  {
			font-family: helvetica, arial;
			margin-top: 15px;
		}
		.nav-link > a {
			text-decoration: none;
			color: #0F7DC2;
		}
		#responseMsg {
			position: relative;
			max-width: 460px;
			margin: 0 auto;
			padding: 20px 10px;
			background-color: #eea29a;
			color: #c94c4c;
			border: thin solid #c94c4c;
			border-radius: 5px;
			font-family: helvetica, arial;
			font-weight: bold;
			text-align: center;
			margin-top: 20px;
			display: none;
		}
		.circle_loader {
 			position: absolute;
 			top: 45%;
 			left: 50%;
 			transform: translate(-50%, -50%);
 			width: 100px;
 			height: 100px;
 			border-radius: 50%;
 			border: 10px solid #588c7e; /*rgba(255, 255, 255, .4);*/
 			border-top: 10px solid #b5e7a0;
 			animation: animate 0.8s infinite linear; 
 		}
 		@keyframes animate {
 			0% {
 				transform: translate(-50%, -50%) rotate(0deg);
 			}
 			100% {
 				transform: translate(-50%, -50%) rotate(360deg);
 			}
 		}
	</style>
	<script type="text/javascript">
		$(function() {
			$('#login').click(function() {
				var email = $('#email').val().trim();
				var password = $('#password').val().trim();
				if($('#remember').is(':checked')) {
					var rememberUser = "on";
				} else {
					var rememberUser = "off";
				}
				if(email == "") {
					return alert("Please enter email!");
				}
				else if(password == "") {
					return alert("Please enter password!");
				}
				else {
					var dataString = {'login':'true', 'email':email, 'password':password, 'remember':rememberUser.trim() }; //"login=true&email="+email+"&password="+password;
					$.ajax({
						url: 'controller.php',
						type: 'POST',
						data: dataString,
						cache: false,
						beforeSend: function() {
							$('#login').html('Loading...');
						},
						success: function(data) {
							var jObjData = JSON.parse(data);
							if(jObjData.response === "success") {
								$('body').html('').addClass('circle_loader');
								// setTimeout(function() {
									$('body').html(jObjData.location_url);
								// }, 1000);
							}
							else if(jObjData.response === "activation") {
								$('#responseMsg').html('<span class="fa fa-warning"></span> Please check email inbox to activate your account!.').show();
							}
							else {
								$('#responseMsg').html('<span class="fa fa-warning"></span> Incorrect email or password!.').show();
							}
							$('#login').html('Login');
						},
						error: function() {
							$('#login').html('Login');
							window.location.href = "errorpages/";
						}
					});
				}
			});
		});
	</script>
</head>
<body>

	<div class="container">
		<div class="heading">Login</div>
		<div class="login-form">
			<form role="form" action="" method="POST" enctype="application/x-www-urlencoded">
				<div class="form-control">
					<div><label class="form-label">Email</label></div>
					<input type="email" class="form-input" id="email" name="email" value="<?php (!empty($_COOKIE['email'])) ? print $_COOKIE['email'] : print''; ?>" autocomplete="off" placeholder="Enter email" autofocus>
				</div>
				<div class="form-control">
					<div><label class="form-label">Password</label></div>
					<input type="password" class="form-input" id="password" name="passwords" value="<?php (!empty($_COOKIE['password'])) ? print $_COOKIE['password'] : print''; ?>" autocomplete="off" placeholder="Enter passwords">
				</div>
				<div class="form-control">
					<div class="remember">
						<label class="radio-inline">
							<input type="checkbox" name="remember_me" <?php if(!empty($_COOKIE['email'])) { ?> checked <?php } ?> id="remember">Remember me
						</label>
					</div>
				</div>
				<div class="form-control">
					<button type="button" id="login" class="btn-login">Login</button>
					<div class="link"><a href="reset">Forgot password?</a></div>
				</div>
			</form>
			
		</div>
		<center><div class="nav-link"><a href="home">&larr; Return Home</a> | <a href="signup">Signup</a></div></center>
		<div id="responseMsg"></div>
	</div>
	
</body>
</html>