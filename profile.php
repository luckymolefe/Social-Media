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
	<title>Profile Sample</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<style type="text/css">
		/*body{
			background: url("backgrounds/img17.jpg");
		}*/
		.container-item {
			max-width: 100%;
			background-color: transparent; /*#f7f7f7;*/
			margin: 0 auto;
			margin-top: 30px;
		}
		.profile-card {
			max-width: 400px;
			margin: 0 auto;
			height: auto;
			background-color: rgba(100, 100, 100, 0.5);/*#ffffff;*/
			color: #444;
			padding-bottom: 20px;
			box-shadow: 1px 2px 5px rgba(0, 0, 0, 0.5);
			margin-bottom: 20px;
			margin-top: 0px;
			font-family: helvetica;
			z-index: 0;
		}
		.profile-card > .userProfile >img {
			width: 100%;
			max-width: 100%;
			margin: 0 auto;
			z-index: 1;
		}
		.close-item { 
			position: absolute;
			float: right;
			z-index: 2;
			font-size: 20px;
			font-weight: bold;
			background-color: transparent;
			color: #eea29a;
			padding: 2px 10px;
			cursor: pointer;
		}
		.close-item:hover {
			background-color: #96ceb4; /*rgba(0, 0, 0, 0.6);*/
			color: #fff;
		}
		.firstname, .lastname, .acc_names {
			text-align: center;
			font-weight: bold;
			font-size: 25px;
			margin-bottom: 5px;
			color: #fff;
		}
		.acc_email {
			text-align: center;
			font-size: 16px;
			font-weight: bold;
			margin-top: 5px;
			color: #fff;
		}
		.acc_status {
			color: #fff;
			font-size: 18px;
			margin-top: 5px;
			text-align: center;
		}
		.profile-change {
			position: absolute;
			margin-top: 363px;
			margin-left: 363px;
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
	</style>
	<script type="text/javascript">
		$(function() {
			$('.live-edit').hide();
			$('.profile-change').hide();
			$('.editable, .btn-save').hide();

			$('.close-item').click(function() {
				$('.container-item').removeClass('slideInDown').addClass('slideOutUp'); //remove
				$('.layer').delay(500).fadeOut('slow'); 
			});

			$('.userProfile').on('mouseenter',function() {
				$('.profile-change').stop(true,true).fadeIn('slow');
				$('.userProfile').on('mouseleave', function() {
					$('.profile-change').fadeOut();
				});
			});

			$('.profile-change').click(function() {
				$.ajax({
					type: "GET",
					url: "upfile.php",
					data: {"email":"<?php echo $email; ?>", "sender":"profile"},
					cache: false,
					success: function(data) {
						$('.container-item').removeClass('slideInDown').addClass('slideOutLeft');
						setTimeout(function() {
							$('.layer').html(data).show();
						}, 600);
					}
				});
			});

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
			        		$(".responseData").html("<center><span class='text-success animated flash'><strong>Account saved successfully!.</strong></span></center>").show();
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
		<div class="profile-card">
			<span class="close-item" title="Close">&times;</span>
			<span class="userProfile">
				<span class="fa fa-camera profile-change" title="Change profile"></span>
				<img src="<?php echo $imagePath; ?>" />
			</span>
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
				<li class="acc_status">Account Status: <strong style="color:#588c7e;"><?php echo $status; ?></strong></li><!-- #55acee -->
				<li class="list-unstyled"><button class="btn btn-success btn-sm btn-block btn-save">Save <span class="fa fa-save"></span></button></li>
			</ul>
			<div class="responseData"></div>
		</div>
	</div>
</body>
</html>