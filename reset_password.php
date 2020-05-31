
<!DOCTYPE html>
<html>
<head>
	<title>Social | Reset Password</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="../boostrap3/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="styles/animate.css">

	<script type="text/javascript" src="../boostrap3/jquery/jquery-1.12.4.min.js"></script>
	<style type="text/css">
		body {
			background: -webkit-linear-gradient(90deg, #d9ecd0 10%, #96ceb4 70%); /*-webkit-linear-gradient(90deg, #0F7DC2 10%, #034f84 70%);*/
			background-repeat: no-repeat;
			background-size: 100% 990px;
			max-width: 100%;
			margin: 0 auto;
		}
		.reset-form {
			max-width: 100%;
			max-width: 400px;
			margin: 0 auto;
			background-color: rgba(245, 245, 245, 0.6); /*#f5f5f5;*/
			padding: 50px 40px;
			border: thin solid #fff;
			border-radius: 5px;
			margin-top: 80px;
			box-shadow: inset 2px 1px 8px rgba(0, 0, 0, 0.3),
						inset -2px -1px 5px rgba(0, 0, 0, 0.3),
							  2px 1px 5px rgba(0, 0, 0, 0.5);
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
			background: url('backgrounds/typicons/mail.png');
			background-repeat: no-repeat;
			background-size: 40px;
		}
		.form-control > input[type="email"]:focus {
			border-color: #66afe9;
			-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(102, 175, 233, .6);
		}
		.btn-reset {
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
		.btn-reset:hover {
			background-color: #e0e2e4;
			color: #999;
			box-shadow: 1px 1px 5px rgba(0, 0, 0, 0.3);
			cursor: pointer;
		}
		.btn-reset:active {
			background: -webkit-linear-gradient(90deg, #96ceb4 0%, #d9ecd0 70%);
			/*background-color: #96ceb4; /*#d9ecd0;*/
			color: #888;
			border-color: #c0c0c0; /*#96ceb4;*/
			outline: 0;
		}
		.btn-reset:disabled {
			background: linear-gradient(#eee 40%, #d0d0d0 100%);
			color: #c0c0c0;
			border: thin solid #c0c0c0;
		}
		.btn-reset:focus {
			outline: 0;
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
	</style>
	<script type="text/javascript">
		$(function() {
			$('.btn-reset').attr('disabled',true);
			$('#email').on('keyup', function() {
				if($('#email').val().trim() != "" && $('#email').val().length >= 10) {
					$('.btn-reset').attr('disabled',false);
				} else {
					$('.btn-reset').attr('disabled',true);
				}
			});

			$('.btn-reset').click(function() {
				var email = $('#email').val().trim();
				$('#responseMsg').html('').hide();
				if(email=="") {
					return alert("Please type your email address.");
				}
				if(email.length < 12) {
					return $('#responseMsg').html("Invalid email address!").show();
				}
				var cancel = confirm('Continue reset password?');
				if(cancel==false) {
					return false;
				}
				else {
					var dataString = {"requestReset":"true", "email":email};
					$.ajax({
						type: "POST",
						url: "controller.php",
						data: dataString,
						cache: false,
						beforeSend:function() {
							$('.btn-reset').html('Sending...');
						},
						success:function(data) {
							var jason = JSON.parse(data);
							if(jason.message === "success") {
								$('#responseMsg').html(jason.response).show();
								window.open(jason.objfile,'_blank');
							} else {
								$('#responseMsg').html(jason.message).show();
							}
							$('.btn-reset').html('Reset');
							$('#email').val('');
							$('#responseMsg').delay(10000).fadeOut(1000);
						},
						error:function() {
							$('.btn-reset').html('Reset');
							alert('Error 404: Url not found!.');
						}
					});
				}
			});
		});
	</script>
</head>
<body>
	<div class="container">
		<div class="reset-form">
			<div align="right" class="form-control">
				<input type="email" class="form-input" id="email" name="reset_password" autocomplete="off" placeholder="Type your email address" required autofocus>
				<button type="button" class="btn-reset">Reset</button>
				<!-- <button>Reset</button> -->
			</div>
		</div>
		<center><div class="nav-link"><a href="home">&larr; Return Home</a> | <a href="login">Login</a></div></center>
		<div id="responseMsg"></div>
	</div>
</body>
</html>