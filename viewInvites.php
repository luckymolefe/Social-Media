<?php
	if(!isset($_GET['viewInvitations'])) {
		return;
		exit();
	}
	$url = (isset($_GET['profUrl'])) ? $_GET['profUrl'] : null;
	$names = (isset($_GET['names'])) ? $_GET['names'] : null;
	$email = (isset($_GET['email'])) ? $_GET['email'] : null;
	$timeStamp = (isset($_GET['dateStamp'])) ? $_GET['dateStamp'] : null;
	$authorizedUser = (isset($_GET['ownerEmail'])) ? $_GET['ownerEmail'] : null;
?>
<!DOCTYPE html>
<html>
<head>
	<title>View Invitations</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<style type="text/css">
		.container-item {
			max-width: 100%;
			max-width: 400px;
			margin: 0 auto;
			background-color: rgba(88, 140, 126, 0.6); /*#588c7e;*/
			color: #fff;
			font-family: helvetica, arial;
			border-radius: 5px;
			padding: 20px 10px;
			margin-top: -140px;
		}
		.message-item {
			font-size: 20px;
			font-weight: bolder;
			margin-bottom: 10px;
		}
		/* accept invitation button styles */
		.btn-accept {
			background: linear-gradient(#588c7e, #96ceb4, #588c7e);
			color: #eee;
			padding: 10px 40px;
			border: thin solid #96ceb4;
			border-radius: 5px;
			font-weight: bolder;
			margin-bottom: 10px;
		}
		.btn-accept:hover {
			cursor: pointer;
			background: linear-gradient(0deg, #588c7e 10%, #96ceb4 100%, #588c7e 10%);
		}
		.btn-accept:active {
			cursor: pointer;
			color: #96ceb4;
			background: linear-gradient(180deg, #588c7e 10%, #96ceb4 100%, #588c7e 10%);
		}
		/* decline invitation button styles */
		.btn-decline {
			background: linear-gradient(#ccc, #f5f5f5, #ccc);
			color: #aaa;
			padding: 10px 40px;
			border: thin solid #96ceb4;
			border-radius: 5px;
			font-weight: bolder;
			margin-bottom: 10px;
		}
		.btn-decline:hover {
			cursor: pointer;
			background: linear-gradient(0deg, #ccc 10%, #f5f5f5 100%, #ccc 10%);
		}
		.btn-decline:active {
			cursor: pointer;
			color: #f5f5f5;
			background: linear-gradient(180deg, #ccc 10%, #f5f5f5 100%, #ccc 10%);
		}
		.close-item {
			position: relative;
			top: -20px;
			right: -10px;
			font-size: 25px;
			font-weight: bold;
			float: right;
			background-color: rgba(150, 206, 180, 0.4); /*#96ceb4;*/
			padding: 0 4px 0 8px;
			border-radius: 0 4px 0 0;
		}
		.close-item:hover {
			cursor: pointer;
			background-color: #96ceb4;
		}
		@media all and (max-width: 496px) { /*hide spacer when screen size is small*/
			.spacer {
				display: none;
			}
		}
		.popup-show {
			animation: popIn 0.8s ease forwards;
		}
		@keyframes popIn {
	      0%  { transform: translateY(-140px) }
	      85% { transform: translateY(270px) }
	      100%{ transform: translateY(250px) }
	    }
		.popup-hide {
	      animation: popoff 1.8s ease forwards;
	    }
	    @keyframes popoff {
	      0%  { transform: translateY(250px) }
	      100%{ transform: translateY(-10px) }
	    }
	    .invitationMessage {
	    	font-size: 18px;
	    	color: #eee;
	    }
	    .invitationMessage > img {
	    	width: 50px;
	    	border-radius: 50%;
	    }
	</style>
	<script type="text/javascript">
		function closePopup(eventdata) { //closing the window popup
			var close = confirm('Close this nofitication?');
			if(close==false) {
				return false;
			}
			eventdata.parentNode.classList.remove('popup-show');
			eventdata.parentNode.classList.add('popup-hide');
			$('.layer').delay(800).fadeOut('slow');
		}
		function acceptInvite() {
			document.getElementById('response_data').innerHTML = '<span class="fa fa-refresh fa-pulse"></span> Processing...';
			var inviteDate = document.getElementById('timeStamp').value;
			formdata = "accepted=true&timeStamp="+inviteDate+"&user=<?php echo $authorizedUser ?>";
			xhr = new XMLHttpRequest();
			xhr.open("POST", "controller.php", true);
			xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhr.onreadystatechange = function() {
				if(xhr.readyState == 4 && xhr.status == 200) {
					var jason = JSON.parse(xhr.responseText);
					document.getElementById('response_data').innerHTML = jason.message; //display message after processing request
					setTimeout(function() {
						pushAlertsCount();
						$('.container-item').removeClass('popup-show').addClass('popup-hide');
						$('.layer').delay(800).fadeOut('slow');
					}, 2000);
				}
			}
			xhr.send(formdata);
		}
		function declineInvite() {
			var thisElem = document.getElementById('btn-decline');
			thisElem.parentNode.parentNode.classList.add('popup-hide');
			$('.layer').delay(800).fadeOut('slow');
		}
	</script>
</head>
<body>
	<div class="container-item popup-show">
		<span class="close-item" title="Cancel" onclick="closePopup(this);">&times;</span>
		<center>
			<div class="message-item">Invitation Friend Request</div>
				<p class="invitationMessage"><img src="<?php echo $url; ?>" /> <?php echo $names; ?></p>
				<input type="hidden" name="timeStamp" id="timeStamp" value="<?php echo $timeStamp; ?>">
			<button type="button" class="btn-accept" title="accept" onclick="acceptInvite()">Accept</button>
			<span class="spacer">&nbsp;</span>
			<button type="button" class="btn-decline" id="btn-decline" title="decline" onclick="declineInvite()">Not Now</button>
		</center>
		<center><div id="response_data"></div></center>
	</div>
</body>
</html>