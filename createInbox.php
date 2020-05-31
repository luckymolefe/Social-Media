<?php
	if(isset($_GET['writeMessage'])) {
		$sender_email = $_GET['senderEmail'];
		$sender_names = $_GET['senderNames'];
		$internalRecipient = (!empty($_GET['secret_recipient'])) ? $_GET['secret_recipient'] : null;
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Contact Form</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
		<style type="text/css">
			/*body {
				background-color: #eee;
				font-family: 'Roboto', sans-serif;
			}*/
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
				padding: 10px 20px 10px 20px;
				border: thin solid #82CAFF; /*3BB9FF*/
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
			input[type='email'], #message {
				text-indent: 33px;
			}
			textarea {
				resize: none;
				font-family: sans-serif, helvetica;
			}
			.form-input:nth-child(2)::before {
				font-family: 'FontAwesome';
				content: '\f0e0';
				position: absolute;
				font-size: 1.8em;
				padding-left: 5px;
				padding-top: 2px;
				color: skyblue;
			}
			.form-input:nth-child(3)::before {
				font-family: 'FontAwesome';
				content: '\f044';
				position: absolute;
				font-size: 2em;
				padding-left: 5px;
				padding-top: 2px;
				color: skyblue;
			}
			h3 {
				background-color: rgba(88, 140, 126, 0.5); /*#588c7e66;*/
				color: #fff;
				/*border-bottom: 4px solid #82CAFF;*/
				border-radius: 10px 10px 0px 0px;
				padding: 5px 0 5px 0;
				margin: -18px -18px 15px -18px;
				text-align: center;
				font-family: sans-serif;
			}
			#progress {
				visibility: hidden;
				color: #fff;
				font-family: sans-serif;
				margin-top: 15px;
			}
			.errorMsg {
				color: #ea4335;
				font-weight: bolder;
			}
			.pop-upload-hide {
		      animation: popoff 0.6s ease forwards;
		    }
		    @keyframes popoff {
		      0%  { transform: translateY(70px) }
		      100%{ transform: translateY(-200px) }
		    }
		</style>

		<script type="text/javascript">

				function _(id) {
					return document.getElementById(id);
				}

				function submitForm() {

					var send = confirm("Continue to send message?"); //confirms on message submission, IF user clicks OK it continues Else Cancel it doesnt continue
					if(send==false) {
						return false;
					} 
					//validate all input fields if they are not empty
					if (_("senderNames").value == "") {
						alert("Please type your names.");
						return false;
					}
					if (_("senderEmail").value == "") {
						alert("Please type your email.");
						return false;
					}
					if (_("recipientEmail").value == "") {
						alert("Please type your email.");
						return false;
					}
					if (_("message").value == "") {
						alert("Please type your message.");
						return false;
					}

					_("btn").disabled = true;
					_('progress').style.visibility = 'visible';
					_("progress").innerHTML = "<span class='fa fa-refresh fa-pulse'></span> Please wait...";

					var type = "POST"; //method to send data
					var url = "controller.php"; //URL PHP file that will receive data

					var formdata = "inboxSend=true&names="+ _("senderNames").value + "&senderEmail="+ _("senderEmail").value + "&recipientEmail="+ _("recipientEmail").value + "&message="+ _("message").value; //data string that will be passed as URL parameters

					var xhr; //prepare variable to assign object data
					//create ajax XMLHttpRequest object,to be able to use its methods/functions
					xhr = new XMLHttpRequest();
					//open to prepapre connection to a webserver for syncronizing data
					xhr.open(type, url, true);
					//Set content-type header information for sending url encoded variable in the request
					xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
					//Access the onreadystatechange event for the XMLHttpRequest object
					xhr.onreadystatechange = function() {
						if(xhr.readyState == 4 && xhr.status == 200) {
							var jason = JSON.parse(xhr.responseText);
							if(jason.responseData == "success") {
								_("progress").innerHTML = "Message sent successfully...";
								//_("names").value = ''; //remove text on each input field
								_("recipientEmail").value = '';
								_("message").value = '';
								_("btn").disabled = false;
								$('#container').delay(800).addClass('pop-upload-hide'); //remove when done sending
							    $('.layer').delay(500).fadeOut('slow');
							} else {
								_("progress").innerHTML = "<span class='errorMsg animated flash'>Failed to send message. "+jason.responseData+"</span>";
								_("btn").disabled = false;
							}
						}
					}
					//now send HTTP request data to PHP and wait for server response
					xhr.send(formdata);

					/*try {
						xhr = new ActiveXObject("Msxml2.XMLHTTP");
					} 
					catch (e) {
						try {
							xhr = new ActiveXObject("Microsoft.XMLHTTP");
						}
						catch (E) {
							xhr = false;
						}
					}
					if (!xhr && typeof XMLHttpRequest != 'undefined') {
						xhr = new XMLHttpRequest();
					}
					xhr.open("GET", "contactFormProcess.php"+formdata, true);
					xhr.onreadystatechange=function() {
						if (xhr.readyState != 4) return;
							alert(xhr.status);
							document.getElementById("progress").innerHTML = xhr.responseText;
							_("btn").disabled = false;
					}
					xhr.send();*/

				} /* onClick Event Function END */
				$('.cancel').click(function(event) {
			      $('#container').addClass('pop-upload-hide'); //remove
			      $('.layer').delay(200).fadeOut('slow'); 
			    });
			    $('.sendBtn').attr('disabled', true);
			    $('#recipientEmail, #message').on('keyup', function() {
			    	// $('.sendBtn').attr('disabled', false);
			    	if($('#recipientEmail').val().trim() == "" || $('#recipientEmail').val().trim() == " " || $('#message').val().trim() == "" || $('#message').val().trim() == " ") {
						$('.sendBtn').attr('disabled', true);
					} else {
						$('.sendBtn').attr('disabled', false);
					}
			    });
			    //this is done automatically when user is selected from friendslist
			    var recipientEmailData = "<?php echo $internalRecipient; ?>"; //get email value from PHP
			    if(recipientEmailData != "") { //if value is empty, then don't disable the element
			    	$('#recipientEmail').val(recipientEmailData);
				    $('#recipientEmail').attr('disabled', true); //if has email address then lock field, else 
				} else {
					$('#recipientEmail').val('');
					 $('#recipientEmail').attr('disabled', false);
				}
		</script>
	</head>
	<body>
		<div class="container animated slideInRight" id="container">
			<form action="" method="POST" enctype="application/x-www-form-urlencoded">
				<div class="header"><h3>Write Message</h3></div>
				<!-- <div><input type="text" id="names" name="names" class="controls" placeholder="Name" autofocus required></div> -->
				<div class="form-input">
					<input type="hidden" id="senderEmail" name="sender_email_add" value="<?php echo $sender_email; ?>">
					<input type="hidden" id="senderNames" name="sender_names" value="<?php echo $sender_names; ?>">
					<input type="email" id="recipientEmail" name="email" class="controls" placeholder="Recipient Email Address" required >
				</div>
				<div class="form-input">
					<textarea id="message" name="message" class="controls" rows="8" placeholder="Type Your Message Here" required></textarea>
				</div>
				<div align="right">
					<button type="reset" id="btn" class="cancel"><span class="fa fa-times"></span> Cancel</button>
					<button type="button" id="btn" onClick="javascript:submitForm()" class="sendBtn"><span class="fa fa-envelope-o"></span> Send</button>
				</div>

				<div id="progress" align="center"></div><span id="status"></span> <!-- <img src="authappoop/images/progress.gif"> -->
			</form>
		</div>

	</body>
</html>

