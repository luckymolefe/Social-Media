<?php

if(isset($_GET['getuserdata'])) {
	$uid = (isset($_GET['uid'])) ? trim($_GET['uid']) : null;
	$email = (isset($_GET['email'])) ? trim($_GET['email']) : null;
	$username = (isset($_GET['username'])) ? trim($_GET['username']) : null;
	$imagePath = (isset($_GET['imageurl'])) ? trim($_GET['imageurl']) : null;
	$status = ($_GET['acc_status'] == "1") ? '<span class="text-success">Active</span>' : '<span class="text-danger">inActive</span>';
	$senderEmail = (isset($_GET['senderEmail'])) ? trim($_GET['senderEmail']) : null;
	$inviteExist = ($_GET['inviteExist'] == "1") ? '1' : '0';
}
else {
	return false;
	exit();
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Profile update</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<style type="text/css">
		.container-item {
			max-width: 100%;
			max-width: 400px;
			margin: 0 auto;
			height: auto;
			background-color: rgba(216, 216, 216, 0.8); /*#d7d7d7;*/
			border-radius: 5px;
			padding: 15px;
			font-family: helvetica, arial;
			text-align: center;
			margin-top: 50px;
		}
		.profile-image > img {
			position: relative;
			left: 110px;
			width: 150px;
			border-radius: 50%;
		}
		@media all and (max-width: 496px) {
			.profile-image > img {
				left: 80px;
			}
		}
		.profile-name {
			font-size: 25px;
			color: #fff; /*#9DB1CC;*/
			font-weight: bold;
		}
		.email {
			color: #034f84; /*#777;*/
			font-weight: bold;
			margin-top: 15px;
		}
		.userstatus {
			border-top: thin solid #ccc;
			border-radius: 5px;
			padding: 10px 15px;
			color: #777;
			font-weight: bold;
			margin-top: 15px;
		}
		.btns {
			border-top: thin solid #ccc;
			margin-bottom: 15px;
			padding-top: 20px;
		}
		.btn-invite {
			width: 120px;
			padding: 10px;
			margin-right: 10px;
			background: linear-gradient(#96ceb4 20%, #588c7e); /*linear-gradient(#9DB1CC 10%, #ccc 50%, #9DB1CC 10%);*/
			color: #fff;
			border: thin solid #9DB1CC;
			border-radius: 5px;
			font-weight: bold;
		}
		.btn-message {
			width: 120px;
			padding: 10px;
			background: linear-gradient(#ded 20%, #9DB1CC);/*#9DB1CC;*/
			color: #7bc5ff;
			border: thin solid #9DB1CC;
			border-radius: 5px;
			font-weight: bold;
		}
		.btn-invite:active {
			background: transparent;
			color: #588c7e;
			border: thin solid #588c7e;
			font-weight: bold;
		}
		.btn-invite:disabled {
			background: #eee;
			color: #d7d7d7;
			border: thin solid #bbb;
		}
		.btn-message:active {
			background: transparent;
			color: inherit;
			border: thin solid #9DB1CC;
			color: #9DB1CC;
			font-weight: bold;
		}
		.responseMessage {
			display: none;
			font-weight: bold;
		}
		.close-item {
			position: relative;
			top: -15px;
			right: -15px;
			font-size: 25px;
			font-weight: bold;
			float: right;
			background-color: transparent; /*rgba(150, 206, 180, 0.4);*/ /*#96ceb4;*/
			padding: 0 4px 0 8px;
			border-radius: 0 4px 0 0;
			/*color: #fff;*/
			color: #d7d7d7;
		}
		.close-item:hover {
			cursor: pointer;
			background-color: #96ceb4;
			color: #fff;
		}
		.back-btn {
			position: relative;
			top: -15px;
			left: -15px;
			font-size: 18px;
			font-weight: bold;
			float: left;
			background-color: transparent; /*rgba(150, 206, 180, 0.9);*/ /*#96ceb4;*/
			padding: 8px;
			border-radius: 4px 0 0 0;
			color: #d7d7d7;
			cursor: pointer;
		}
		.back-btn:hover {
			background-color: rgba(150, 206, 180, 0.9);
			color: #fff;
		}
		.popup-hide {
	      animation: popoff 1.2s ease forwards;
	    }
	    @keyframes popoff {
	      0%  { transform: translateY(50px) }
	      100%{ transform: translateY(-430px) }
	    }
	</style>
	<script type="text/javascript">

		function getEvent(eventdata,refUIData, refMailTo, refMailFrom) { //this would process any click action Invite/send message and invoke relevant functions 
			if(eventdata == 'message') {
				var cancel = confirm('Want to send message?');
				if(cancel==false) {
					return;
				}
				$('.container-item').addClass('slideOutUp'); //add animation on hiding the profile view window
				var sendMessageTo = refUIData;
				setTimeout(function() {
					writeMsg(sendMessageTo); //call write message function to bring Compose Message window.
				}, 600);
			} else {
				var cancel = confirm('Want to send invitation?');
				if(cancel==false) {
					return;
				}
				sendInvitation(refUIData, refMailTo, refMailFrom); //call a function to send invitation message/notification
			/*	if(sendInvitation(refData)) {
					// eventdata.classList.add('classname'); //for adding class to element using pure JS
					eventdata.classList.remove('result-expand'); //for removing class from element using pre JS
					eventdata.classList.add('invited'); //add new class to show faded text
					eventdata.innerHTML = " Invited"; //change element text
					eventdata.removeAttribute('onclick'); //lastly remove onclick event attribute from element
					// alert("Invitation Sent to, "+refData); //or call an invite function
				} else {
					alert("Failed to send invitation!");
				}*/
			}
		}

		function sendInvitation(refUid, refToEmail, refFromEmail) { //function send invitation to a controller, to process request
			var formdata = "invite=true&uid="+refUid+"&inviteTo="+refToEmail+"&inviteFrom="+refFromEmail; //url strings to send
			var xhr = new XMLHttpRequest(); //create http-request object
			xhr.open("POST", "controller.php", true); //define url to received data and method, used to send data
			xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded"); //encode url since its send via post method
			xhr.onreadystatechange = function() {
		    	if (xhr.readyState == 4 && xhr.status == 200) { //ready state and finished
		    		$('.responseMessage').removeClass('animated flash shake text-success text-danger');
		    		var jason = JSON.parse(xhr.responseText);
		    		if(jason['message'] != 'failed') {
		    			// alert(jason['message']);
		    			$('.responseMessage').addClass('animated flash text-success').html(jason['message']).show();
		    			$('.btn-invite').attr('disabled', true);
						$('.btn-invite').html('Invited');
		    		} 
		    		if(jason['message'] == 'failed') {
		    			$('.responseMessage').addClass('animated shake text-danger').html("Failed to send invitation, please try again!.").show();
		    		}
		    		$('.responseMessage').delay(10000).fadeOut('slow');
		    	}
		    }
		    xhr.send(formdata);
		}

		function closePopup(eventdata) { //closing the window popup
			/*var close = confirm('Close this popup?');
			if(close == false) {
				return false;
			}*/
			eventdata.parentNode.classList.remove('swing');
			eventdata.parentNode.classList.add('hinge'); //popup-hide
			$('.layer').delay(1500).fadeOut('slow');
		}

		function goBack(eventdata) {
			eventdata.parentNode.classList.remove('swing');
			eventdata.parentNode.classList.add('slideOutUp'); //popup-hide
			setTimeout(function() {
				$.get("filterusers.php", {"filter":"true"}, function(data) {
					$('.layer').html(data).show();
				});
			}, 500);
		}

		var isInvited = <?php echo $inviteExist; ?>;
		if(isInvited == "0") { //if invitation status is 0 means is not already invited,
			$('.btn-invite').attr('disabled', false);
			$('.btn-invite').prop('title','Send invitation');
		} else { //else user already invited, disable invite button.
			$('.btn-invite').attr('disabled', true);
			$('.btn-invite').prop('title','Invited');
			$('.btn-invite').html('Invited');
		}
		
	</script>
</head>
<body>
	<div class="container-item animated swing">
		<span class="back-btn glyphicon glyphicon-arrow-left" title="Go Back" onclick="goBack(this);"></span> <!-- glyphicon-arrow-left -->
		<span class="close-item" title="Close" onclick="closePopup(this);">&times;</span>
		<div class="profile-image">
			<img src="<?php echo $imagePath; ?>"/> <!-- profile/facebook_picture.png -->
		</div>
		<div class="profile-name"><?php echo $username; ?></div>
		<div class="email"><?php echo $email; ?><!-- luckmolf@company.com --></div>
		<div class="userstatus">Account Status: <?php echo $status; ?></div>
		<div class="btns">
			<button type="button" class="btn-invite" onclick="getEvent(null, '<?php echo $uid; ?>', '<?php echo $email; ?>', '<?php echo $senderEmail; ?>')">Invite</button>
			<button type="button" class="btn-message" title="Send message" onclick="getEvent('message','<?php echo $email; ?>')">Message</button>
		</div>
		<div class="responseMessage"></div>
	</div>
</body>
</html>