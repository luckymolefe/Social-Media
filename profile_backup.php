<?php
if(!isset($_GET['userprofile'])) { //if request was not sent to server then exit;
	return false;
	exit();
}
$uid = (isset($_GET['uid'])) ? $_GET['uid'] : '';
$firstname = (isset($_GET['firstname'])) ? $_GET['firstname'] : '';
$lastname = (isset($_GET['lastname'])) ? $_GET['lastname'] : '';
$email = (isset($_GET['email'])) ? $_GET['email'] : '';
$imagePath = (isset($_GET['urlpath'])) ? $_GET['urlpath'] : 'profile/avatars/facebook_picture.png';
$status = ($_GET['acc_status'] == "1") ? 'Active' : 'inActive';
//process user profile information
?>
<!DOCTYPE html>
<html>
<head>
	<title>Account Profile</title>
	<!-- <link rel="stylesheet" type="text/css" href="../boostrap3/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="../boostrap3/font-awesome/css/font-awesome.min.css">
	<script type="text/javascript" src="../boostrap3/jquery/jquery-1.12.4.min.js"></script>
	<script type="text/javascript" src="../boostrap3/js/bootstrap.min.js"></script> -->
	<style type="text/css">
		.container-item {
			background-color: rgba(150, 206, 180, 0.5);
			max-width: 100%;
			max-width: 550px;
			margin: 15px auto;
			margin-top: 50px;
			padding: 18px;
			border-radius: 10px;
			box-shadow: inset 5px 5px 6px rgba(255, 255, 255, 0.4), 
						inset -4px -4px 6px rgba(255, 255, 255, 0.4), 
						3px 3px 8px rgba(0, 0, 0, 0.4);

		}
		.profile-image {
			max-width: 100%;
			margin: 0 auto;
			margin-right: 300px;
		}
		.profile-image > img {
			/*max-width: 100%;*/
			width: 185px;
			border: 6px solid #f7f7f7;
			border-radius: 3px;
			margin-bottom: 15px;
			border-radius: 50%
		}
		.content {
			/*width: 300px;*/
			/*margin-top: -190px;*/
			/*margin-left: 200px;*/
			width: 100%;
			margin: 0 auto;
			background-color: rgba(0, 0, 0, 0.5);
			border-radius: 5px;
			padding: 10px;
		}
		.profile-change {
			position: absolute;
			margin-top: 70px;
			margin-left: 180px;
			font-size: 20px;
			color: #fff;
			cursor: pointer;
			padding: 8px;
		}
		.profile-change:hover {
			background: rgba(255, 255, 255, 0.5);
			border: none;
			border-radius: 4px;
		}
		.profile-change:active {
			color: #87bdd8;
			background: rgba(200, 200, 200, 0.5);
		}
		.cancel {
			font-size: 18px;
			float: right;
			margin-top: -12px;
			margin-right: -10px;
			cursor: pointer;
		}
		.cancel:hover {
			color: #f7786b;
		}
		.cancel:active {
			color: #c94c4c;
		}
		@media all and (max-width: 496px) {
			.content {
				max-width: 100%;
				position: relative;
				margin-top: 0px;
				margin-left: 0px;
			}
			.content-extend {
				max-width: 100%;
				width: 300px;
			}
			.profile-image {
				width: 100%;
				max-width: 100%;
				margin: 0 auto;
			}
			.profile-image > img {
				position: relative;
				width: 300px;
				max-width: 100%;
				margin: 0 auto;
				border-radius: 50%;
				margin-left: 0px;
				margin-bottom: 10px;
			}
		}
		.live-edit {
			cursor: pointer;
			color: #fff;
			font-size: 22px;
		}
		.live-edit:hover {
			color: #55acee;
		}
		.live-edit:active {
			color: green;
		}
		.responseData {
			display: none;
		}
		
		.acc_names {
			color: #fff;
			font-size: 25px;
			font-weight: bold;
			text-align: center;
		}
		.acc_email {
			color: #fff;
			font-size: 20px;
			text-align: center;
		}
		.acc_status {
			color: #fff;
			font-size: 18px;
			text-align: center;
		}
	</style>
	<script type="text/javascript">
		$(function() {
			$('.live-edit').hide();
			$('.profile-change').hide();

			$('.cancel').click(function() {
				$('.container-item').removeClass('slideInDown').addClass('slideOutUp'); //remove
				$('.layer').delay(500).fadeOut('slow'); 
			});
			$('.profile-image').on('mouseenter', function() {
				$('.profile-change').stop(true, true).fadeIn('slow');
				$('.profile-image').on('mouseleave', function() {
					$('.profile-change').fadeOut('slow');
				});
			});
			$('.profile-change').click(function() {
				$.ajax({
					type: "GET",
					url: "upfile.php",
					data: "email=<?php echo $email; ?>&sender=profile",
					cache: false,
					success: function(data) {
						$('.container-item').removeClass('slideInDown').addClass('slideOutLeft');
						setTimeout(function() {
							$('.layer').html(data).show();
						}, 600);
					}
				});
			});

			$('.editable, .btn-save').hide();

			$('.live-edit').each(function() {
				var thisElm = $(this).closest('li');
				thisElm.on('mouseenter',function() {
					thisElm.find('.live-edit').stop(true, true).show();
				});
				thisElm.on('mouseleave',function() {
					thisElm.find('.live-edit').fadeOut('fast');
				});
				$(this).on('click', function() {
					$(this).parent().find('.view').hide();
					$(this).parent().find('.editable').show().focus();
					$(this).parent().find('.live-edit').hide();
					$('.btn-save').show();
				});
			});

			$('body').delegate('.editable','blur', function() { //on change event save data
				$('.editable, .btn-save').hide();
				$('.view').show();
				var dataValue = $(this).parent().find('.editable').val(); //get value after changed
				$(this).parent().find('.view').text(dataValue); //then display on textView
				var thisElm = $(this).closest('li');
				runAjax(thisElm); //call function to save data
			});
		});

		function runAjax(thisElm) {
			var uid = $('#userId').data('uid'); //the user id
			var firstname = $('#firstname').val().trim(); //get the current value inside textboxes
			var lastname = $('#lastname').val().trim();
			var email = $('#email').val().trim();
			if(firstname == "" && lastname == "" && email == "" && uid == "") {
				return $(".responseData").html("<center><span class='text-danger animated flash'><strong>Please type all required details.</strong></span></center>").show();
			}
			else {
				var urldata = {"updateprofile":"true","uid":uid,"firstname":firstname,"lastname":lastname,"email":email};
				$.ajax({
			        url : 'controller.php',
			        type : 'POST',
			        data : urldata,
			        success: function(data) {
			        	//thisElm.find('.view').text(data); //update readOnly textview
			        	//thisElm.find('.editable').val(data); //update hidden textbox value
			        	var jason = JSON.parse(data);
			        	if(jason.message == "success") {
			        		$(".responseData").html("<center><span class='text-primary animated flash'><strong>Saved Successfully!.</strong></span></center>").show();
			        	}
			        	else {
			        		$(".responseData").html("<center><span class='text-danger animated flash'><strong>Failed to save data.</strong></span></center>").show();
			        	}
			        }
			    });
			}
			return 0;
		}
	</script>
</head>

<body>
	<div class="container-item animated slideInDown">
		<span class="fa fa-times cancel" title="Close"></span>
		<div class="profile-image">
			<span class="fa fa-camera profile-change" title="Change profile"></span>
			<img src="<?php echo $imagePath; ?>" />
		</div>
		<div class="content">
			<ul class="list-unstyled">
				<span id="userId" data-uid="<?php echo $uid; ?>"></span>
				<li class="acc_names">
					<span class="view"><?php echo $firstname; ?></span>
					<input type="text" class="form-control editable" id="firstname" name="firstname" value="<?php echo $firstname; ?>" placeholder="Firstname">
					<span class="fa fa-pencil text-muted live-edit pull-right" title="Edit"></span>
				</li>
				<li class="acc_names">
					<span class="view"><?php echo $lastname; ?></span>
					<input type="text" class="form-control editable" id="lastname" name="lastname" value="<?php echo $lastname; ?>" placeholder="Lastname">
					<span class="fa fa-pencil text-muted live-edit pull-right" title="Edit"></span>
				</li>
				<li class="acc_email">
					<span class="view"><?php echo $email; ?></span>
					<input type="email" class="form-control editable" id="email" name="email" value="<?php echo $email; ?>" placeholder="email">
					<!-- <span class="fa fa-pencil text-muted live-edit pull-right" title="Edit"></span> -->
				</li>
				<li class="acc_status text-primary">Account Status: <strong style="color:#55acee;"><?php echo $status; ?></strong></li>
				<li class="list-unstyled"><button class="btn btn-success btn-sm btn-block btn-save">Save <span class="fa fa-save"></span></button></li>
			</ul>
			<div class="responseData"></div>
		</div>
	</div>
</body>
</html>