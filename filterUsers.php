<?php
//filter users by names
if(!isset($_GET['filter'])) {
	return false;
	exit();
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Filter users by names</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- <link rel="stylesheet" type="text/css" href="../boostrap3/font-awesome/css/font-awesome.min.css"> -->
	<!-- <script type="text/javascript" src="../boostrap3/jquery/jquery-1.12.4.min.js"></script> -->
	<style type="text/css">
		.container-item {
			max-width: 100%;
			max-width: 550px;
			height: 350px;
			margin: 50px auto;
			background-color: rgba(88, 140, 126, 0.8);
			padding: 10px;
			border-radius: 10px;
			box-shadow: inset 5px 5px 6px rgba(255, 255, 255, 0.4), 
							inset -4px -4px 6px rgba(255, 255, 255, 0.4), 
							3px 3px 8px rgba(0, 0, 0, 0.4);
			overflow-y: hidden;
		}
		#searchFriends {
			width: 100%;
			/*max-width: 325px;*/
			margin: 0 auto;
			text-indent: 28px;
			padding: 8px 0 8px 0;
			/*margin-top: 5px;*/
			font-size: 15px;
			border-radius: 5px;
			border: thin solid #588c7e;
		}
		.search-input::before {
			position: absolute;
			/*top: 44px;*/
			content: '\f002';
			font-family: 'FontAwesome', arial, helvetica;
			font-size: 18px;
			color: #777;
			padding-left: 8px;
			padding-top: 10px;
		}
		#results {
			position: relative;
			top: -1px;
			max-width: 100%;
			/*max-width: 500px;*/
			margin: 0 auto;
			max-height: 200px;
			overflow-y: auto;
			display: none;
			background-color: rgba(88, 140, 126, 0.8); /*#588c7e; /*#f5f5f5;*/
			color: #fff;
			list-style-type: none;
			padding: 5px 0 5px 0;
			-webkit-box-shadow: 1px 2px 2px rgba(0, 0, 0, 0.3);
			border-right: thin solid #ccc;
			border-bottom: thin solid #999;
			border-left: thin solid #ccc;
			border-radius: 0 0 8px 8px;
			text-indent: 10px;
			font-family: helvetica, arial;
			overflow-y: auto;
		}
		.result-item {
			padding: 8px 0 8px 0;
			/*background-color: rgba(150, 206, 180, 0.5);*/
			/*border-top: thin solid #fff;*/
			border-bottom: thin solid #fff;
		}
		.result-item:hover {
			background-color: #96ceb4; /*#96ceb4;/*#0F7DC2;*/
			color: #fff;
			cursor: pointer;
		}
		.result-item > img {
			width: 25px;
			float: left;
			margin-left: 5px;
			padding-bottom: 3px;
			border-radius: 50px;
			box-shadow: 2px 1px 5px rgba(255, 255, 255, 0.5);
		}
		.result-item-none {
			background-color: #96ceb4;
			padding: 8px 0 8px 0;
			color: #d96459;
			font-weight: bolder;
		}
		.heading {
			font-family: helvetica, arial;
			font-size: 18px;
			color: #fff;
			margin-bottom: 5px;
		}
		.result-output {
			background-color: #66b3ff; /*#405d27;*/
			padding: 8px 10px 8px 0;
			color: #f5f5f5;
			list-style-type: none;
			font-family: helvetica, arial;
			font-size: 18px;
			font-weight: bolder;
			text-indent: 10px;
			border-radius: 5px;
			margin-top: 5px;
		}
		.result-output:hover {
			background-color: #b3d9ff; /*#405d27;*/
			cursor: pointer;
		}
		.result-output:active {
			background-color: #f5f5f5;
			color: #66b3ff;
		}
		.result-output > img {
			width: 25px;
			float: left;
			margin-left: 5px;
			box-shadow: 2px 1px 5px rgba(0, 0, 0, 0.5);
		}
		.result-expand {
			float: right;
		}
		.result-expand:hover {
			/*background-color: #777;*/
			cursor: pointer;
			color: #134d00;
		}
		.friends-output-header {
			background-color: #8b9dc3; /*#405d27;*/
			padding: 8px 10px 8px 0;
			color: #f5f5f5;
			list-style-type: none;
			font-family: helvetica, arial;
			font-size: 18px;
			font-weight: bolder;
			text-indent: 10px;
			border-radius: 5px;
			margin-top: 5px;
		}
		.friends-output-header:nth-child(2) {
			cursor: pointer;
		}
		.friends-output {
			background-color: #66b3ff; /*#405d27;*/
			padding: 8px 10px 8px 0;
			color: #f5f5f5;
			list-style-type: none;
			font-family: helvetica, arial;
			font-size: 18px;
			font-weight: lighter;
			text-indent: 10px;
			border-radius: 5px;
			margin-top: 5px;
		}
		.friends-output > img {
			width: 30px;
			float: left;
			margin-left: 5px;
			border-radius: 2px;
			box-shadow: 2px 1px 5px rgba(0, 0, 0, 0.5);
		}
		.friends-output:hover {
			background-color: #87bdd8;
			color: #eee;
			cursor: pointer;
		}
		.friends-output:active {
			background-color: #cdd6dd;
			color: #777;
		}
		.invited {
			float: right;
			color: #777;
		}
		#selectedOption {
			display: none;
			overflow-y: auto;
		}
		.closefilter {
			float: right;
			font-size: 25px;
			font-weight: bolder;
			color: #fff;
			text-decoration: none;
			margin-top: -15px;
		}
		.closefilter:hover {
			color: #c83349;
			text-decoration: none;
		}
		.closefilter:active {
			color: #0066cc;
			text-decoration: none;
		}
		.closefilter:visited {
			text-decoration: none;
		}
		.pop-upload-hide {
		    animation: popoff 0.8s ease forwards;
	    }
	    @keyframes popoff {
	      	0%  { transform: translateY(30px) }
	      	100%{ transform: translateY(-300px) }
	    }
	    .pendingInvites {
	    	color:#fff;
	    	background: #c94c4c;
	    	border-radius:50%;
	    	padding: 2px 6px;
	    	font-size: lighter;
	    }
	</style>

	<script type="text/javascript">
		function filter(str) {
		    var str = document.getElementById("searchFriends").value;
		    if(str=="" || str==" ") {
		      document.getElementById("results").innerHTML = null;
		      document.getElementById("results").style.display = "none";
		      document.getElementById("selectedOption").innerHTML = null;
		      document.getElementById("selectedOption").innerHTML = getFriendsList(); //reset list first then, call friends list again
		        return false;
		    }
		    if(str.length >= 3) { //if letters are greater or equal to 3 then start to retrieve data
			    var xmlhttp = new XMLHttpRequest();
			    xmlhttp.onreadystatechange = function() {
			    	if(xmlhttp.readyState == 1) {
			    		document.getElementById("results").style.display = "block";
			    		document.getElementById("results").innerHTML = "<li><center><span class='fa fa-spinner fa-pulse'></span> Loading...</center></li>"
			    	}
			        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			        	var jasondata = JSON.parse(xmlhttp.responseText);
			            document.getElementById("results").style.display = "block";
			            document.getElementById("results").innerHTML = jasondata.results; //"<li>"+xmlhttp.responseText+"</li>";
			        }
			    }
			    xmlhttp.open("GET", "controller.php?queryusers=" + str, true);
			    xmlhttp.send();
			}
		}

		function getUser(thisValue, inviteSender) {
			document.getElementById("selectedOption").style.display = "block";
			var eventResponseEmail = thisValue.dataset.email; //to get user ID
			var eventResponseName = thisValue.dataset.names;
			var eventResponseUrl = thisValue.dataset.url;
			var eventResponse_uid = thisValue.dataset.uid;
			var eventResponseStatus = thisValue.dataset.accountstatus;
			var eventResponseInvited = thisValue.dataset.invited;
			/*var tempHtml = "<li class='result-output'><img src='"+eventResponseUrl+"'/>"+eventResponseName
						+"<span onclick='getEvent(\"message\",\""+eventResponseEmail+"\")' class='fa fa-envelope result-expand'> Send Message</span><span onclick='getEvent(this,\""+eventResponse_uid+"\",\""+eventResponseEmail+"\",\""+inviteSender+"\")' class='fa fa-gift result-expand'> Invite</span></li>";*/
			var tempHtml = "<li title='View this profile' class='result-output' data-accountstatus="+eventResponseStatus+" data-invited="+eventResponseInvited+" data-senderemail="+inviteSender+" data-imageurl="+eventResponseUrl+" data-username="+eventResponseName+" data-email="+eventResponseEmail+" data-uid="+eventResponse_uid+" onclick='getProfile(this)'>"
						  +"<img src='"+eventResponseUrl+"' />"
						  +eventResponseName+
						  "</li>";
			document.getElementById("searchFriends").value = null;
			document.getElementById("searchFriends").value = eventResponseName; //keep the search textbox with name that was chosen
			
			document.getElementById("selectedOption").innerHTML = tempHtml; //and write the selected user data to selected results item-list
			document.getElementById("results").style.display = "none";
		}

		function getProfile(eventdata) { //get from click event, and open profile view window popup, with user details
			var userId = eventdata.dataset.uid;
			var email = eventdata.dataset.email;
			var username = eventdata.dataset.username;
			var urlpath = eventdata.dataset.imageurl;
			var accStatus = eventdata.dataset.accountstatus;
			var senderEmail = eventdata.dataset.senderemail;
			var inviteExist = eventdata.dataset.invited;
			var xhr = new XMLHttpRequest();
			var urldata = "profile2.php?getuserdata=true&uid="+userId+"&email="+email+"&username="+username+"&imageurl="+urlpath+"&acc_status="+accStatus+"&senderEmail="+senderEmail+"&inviteExist="+inviteExist;
			xhr.open("GET", urldata, true);
			xhr.onreadystatechange = function() {
				if(xhr.readyState == 4 && xhr.status == 200) {
					$('.layer').html(xhr.responseText);
				}
			}
			xhr.send();
		}

		/*function getEvent(eventdata,refUIData, refMailTo, refMailFrom) { //this would process any click action Invite/send message and invoke relevant functions 
			if(eventdata == 'message') {
				var cancel = confirm('Want to send message?');
				if(cancel==false) {
					return;
				}
				var sendMessageTo = refUIData;
				writeMsg(sendMessageTo); //call write message function to bring Compose Message window.
			} else {
				var cancel = confirm('Want to send invitation?');
				if(cancel==false) {
					return;
				}
				sendInvitation(eventdata, refUIData, refMailTo, refMailFrom); //call a function to send invitation message/notification
				//if(sendInvitation(refData)) {
					// eventdata.classList.add('classname'); //for adding class to element using pure JS
					eventdata.classList.remove('result-expand'); //for removing class from element using pre JS
					eventdata.classList.add('invited'); //add new class to show faded text
					eventdata.innerHTML = " Invited"; //change element text
					eventdata.removeAttribute('onclick'); //lastly remove onclick event attribute from element
					// alert("Invitation Sent to, "+refData); //or call an invite function
				} else {
					alert("Failed to send invitation!");
				}//
			}
		}*/
		//unUsed now
		/*function sendInvitation(eventdata, refUid, refToEmail, refFromEmail) { //function send invitation to a controller, to process request
			var formdata = "invite=true&uid="+refUid+"&inviteTo="+refToEmail+"&inviteFrom="+refFromEmail; //url strings to send
			var xhr = new XMLHttpRequest(); //create http-request object
			xhr.open("POST", "controller.php", true); //define url to received data and method, used to send data
			xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded"); //encode url since its send via post method
			xhr.onreadystatechange = function() {
		    	if (xhr.readyState == 4 && xhr.status == 200) { //ready state and finished
		    		var jason = JSON.parse(xhr.responseText);
		    		if(jason['message'] != 'failed') {
		    			eventdata.classList.remove('result-expand'); //for removing class from element using pure JS
		    			eventdata.classList.add('invited'); //add new class to show faded text
		    			eventdata.innerHTML = " Invited"; //change element text
		    			eventdata.removeAttribute('onclick'); //lastly remove onclick event attribute from element
		    			alert(jason['message']);
		    		} 
		    		if(jason['message'] == 'failed') {
		    			alert("Failed to send invitation, please try again!.");
		    		}
		    	}
		    }
		    xhr.send(formdata);
		}*/

		function getFriendsList() {
			$.get("controller.php", {"allfriends":"true"}, function(data) {
				$('#selectedOption').html(data).show();
			});
		}
		getFriendsList() //invoke function to get firends list on page load

		function closeFilter() {
			/*var cancel = confirm('Close this?');
			if(cancel==false) {
				return;
			}*/
			// $('.container-item').addClass('pop-upload-hide'); slideInRight
			$('.container-item').removeClass('slideInRight').addClass('slideOutRight');
			$('.layer').delay(500).fadeOut('slow');
		}
		$('#searchFriends').focus();
		$('#searchFriends').click(function() {
			$('#searchFriends').select();
		});
		/*var cars = document.querySelector("[data-list='cars']");
			cars.addEventListener('click', function(e) {
			alert("available colors: "+e.target.dataset.colors);
		});*/
	</script>
</head>
<body>
	<div class="container-item animated slideInRight">
		<div class="heading">Filter Friends: <a href="javascript:closeFilter()" class="closefilter" title="Close">&times;</a></div>
		<div class="search-input">
			<input type="search" size="50px" name="q" class="form-control input-lg" id="searchFriends" onKeyup="filter(this.value)" placeholder="type name to search" autocomplete="off">
		</div>
		<div>
			<ul id="results" class="update"></ul>
		</div>
		<div>
			<div id="selectedOption">
				<!-- <li class="result-output">Friend Name 1</li>
				<li class="result-output">Friend Name 2</li>
				<li class="result-output">Friend Name 3</li> -->
			</div>
		</div>
		<!-- <div>
			<input type="text" name="chosenFriend" id="selectedOption" placeholder="name of selected">
		</div> -->
		<!-- <ul class="vehicles" data-list="cars">
			<li data-colors="white">Fiat</li>
			<li data-colors="red">BMW M3</li>
			<li data-colors="black">Audi R8 Coupe</li>
		</ul> -->
	</div>
</body>
</html>