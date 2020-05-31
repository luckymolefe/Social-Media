<?php
require_once('controller.php');
// $user->page_protected(); //restrict page, only who have been authorized
$_SESSION['authorize'] = (isset($_SESSION['authorize'])) ? $_SESSION['authorize'] : null;
if(class_exists('Users')) {
	(method_exists($user, 'isLoggedOn')) ? $loggedon = $user->isLoggedOn() : null;
	(method_exists($user, 'getUserProfile')) ? $userData = $user->getUserProfile(null) : null;	
}
if($loggedon==true) {
	if(( time() - $_SESSION['active_time']) > 900 ) { //is time is older than 1 minute. time in seconds 60sec == 1min. 900sec is 15min
		header("Location: signout/true"); //else the user out
	}
	else {
		$user->updateUserBrowserActivity(); //call function to else create new time session
		#$_SESSION['active_time'] = time(); //else create new time session
	}
}
/*if($loggedon==true) { //if user is logged-in
	$checkins = $post->getPlaces(); //update list of places visited
	$inbox = $msg->getunreadMessages($_SESSION['authorize']); //update inboxMessages number
	$invites = $notifyObj->getInvitations($userData->email); //update invitation numer
	$friendsList = $notifyObj->getFriendsList($userData->email); //update friendsList number
	//start count if variables are not empty/null, else assign zero
	$countCheckins = (!empty($checkins)) ? count($checkins) : 0;
	$inboxCount = (!empty($inbox)) ? count($inbox) : 0;
	$inviteCount = (!empty($invites)) ? count($invites) : 0;
	$countFriends = (!empty($friendsList)) ? count($friendsList) : 0;
} else {
	$countCheckins = 0;
	$inboxCount = 0;
	$inviteCount = 0;
	$countFriends = 0;
}

$notifications = array('messages'=> $inboxCount, 'alerts'=> '0', 'groups'=> $countFriends, 'invitations'=> $inviteCount, 'places'=> $countCheckins);
$messages = $notifications['messages'];
$alerts = $notifications['alerts'];
$groups = $notifications['groups'];
$invitations = $notifications['invitations'];
$places = $notifications['places'];*/

?>
<!DOCTYPE html>
<html>
<head>
	<title>Social | Welcome</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="../boostrap3/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="../boostrap3/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="styles/animate.css">

	<script type="text/javascript" src="../boostrap3/jquery/jquery-1.12.4.min.js"></script>
	<script type="text/javascript" src="../boostrap3/js/bootstrap.min.js"></script>

	<style type="text/css">
		body {
			background-color: #c8c3cc;
		}
		/*custom scroll-bar for webkit google-chrome we apply to All * not only body-element*/
		*::-webkit-scrollbar {
	      width: .75em;
	    }
	    *::-webkit-scrollbar-track {
	      box-shadow: inset;
	      background-color: #F7F7F7;
	    }
	    *::-webkit-scrollbar-thumb {
	      background-color: #588c7e;
	      outline: 1px solid slategrey;
	      border-radius: 10px;
	    }
		.container-fluid {
			width: 100%;
			min-height: 500px; /*500px*/
			max-height: auto;
			/*height: auto;*/
			/*background: #d5e1df;*/
		}
		.bgCover { /*the class for background-cover fixed image*/
			position: fixed;
			background-color: #8ca3a3;
			width: 100%;
			margin: 0 auto;
			top: 0px;
			z-index: 2;
		}
		.bg-2 {
			background-color: #848f4f;
			width: 100%;
			margin: 0 auto;
		}
		.bg-3 {
			background-color: #F7F7F7;
			width: 100%;
			margin: 0 auto;
			margin-top: 210px;
			padding-top: 150px;
		}
		.background-img  {
			overflow-y: hidden;
			width: 100%;
			height: 250px;
			max-width: 100%;
			margin: 0 auto;
			/*border: thin solid #000;*/
			/*background-image: url(backgrounds/breik3.jpg);
			background-size: cover;*/
		}
		.background-img > img {
			/*position: relative;*/
			top: -700px;
			width: 100%;
			height: 280px; /*300px*/
			z-index: 0;
		}
		.user-profile-img > img {
			position: fixed;
			top: 180px;
			left: 40px;
			width: 200px;
			/*width: 190px;*/
			height: 156px;
			border-radius: 5px;
			border: 5px solid #fff;
			-webkit-box-shadow: 2px 3px 8px rgba(0, 0, 0, 0.8);
			max-width: 100%;
			margin: 0 auto;
			z-index: 5;
		}
		.upload {
			position: fixed;
			top: 300px;
			left: 193px;
			/*width: 190px;*/
			height: auto;
			/*background-color: rgba(0, 0, 0, 0.5);*/
			color: #fff;
			padding: 0px 10px;
			border-radius: 5px;
			cursor: pointer;
			font-size: 1.5em;
			display: none;
			z-index: 5;
		}
		@media all and (max-width: 768px) {
			.user-profile-img > img {
				position: fixed;
				top: 200px;
				width: 150px;
				height: 106px;
			}
			.upload {
				position: fixed;
				top: 280px;
				left: 139px;
				font-size: 1.2em;
			}
		}
		.upload-bg {
			position: absolute;
			top: 215px;
			right: -5px;
			height: auto;
			padding: 0px 15px;
			border-radius: 5px;
			color: #fff;
			font-size: 1.8em;
			cursor: pointer;
			display: none;
		}
		.upload:hover, .upload-bg:hover {
			background-color: rgba(255, 255, 255, 0.3);
			border: thin solid #eee;
		}

		.upload:active, .upload-bg:active {
			color: #55ACEE;
		}
		/*.user-content {
			height: 500px;
		}*/
		.navbar {
			position: fixed !important;
			width: 100%;
			background-color: #96ceb4;
			/*border-color: #96ceb4 !important;*/
			border: none;
			-webkit-box-shadow: 0 3px 6px rgba(0,0,0,0.5);
			border-radius: 0px !important;
		}
		.navbar-brand {
			margin-left: 240px;
		}
		.navbar-brand {
			color: #fff !important;
		}
		.navbar-nav > li > a {
			color: #fff !important;
		}
		.navbar-nav > li > a:hover, .navbar-nav li.active a {
			background-color: #588c7e !important;
		}
		.navbar-brand:hover {
			color: #405d27 !important;
		}
		/*.navbar-nav > li:nth-child(4) {
			padding-right: 20px !important;
		}*/
		/*.list-group {
			position: fixed;
			width: 350px;
		}*/
		.menu1 li:hover {
			/*color: #777;*/
			background-color: #d5e4e6 !important;
			cursor: pointer;
		}
		.icon-profile > img {
			width: 40px;
			float: left;
			/*text-align: bottom;*/
		}
		.username {
			position: relative;
			top: 7px;
			font-family: helvetica;
			font-weight: lighter;
			font-size: 1.2em;
			padding-left: 10px;
		}
		/*.posts { controls to contain inner posts
			max-width: 100%;
			max-height: 450px;
			overflow-y: auto;
		}*/
		.post-time {
			position: relative;
			top: 10px;
			font-family: helvetica;
			font-size: 1.2em;
			font-weight: bolder;
		}
		.post-item-card {
			/*overflow: auto;*/
			/*text-overflow: break-word;*/
			/*position: relative;*/
			max-width: 800px;
			margin: 0 auto;
			height: auto;
			background-color: #ffffff;
			color: #777;
			padding-bottom: 20px;
			box-shadow: 1px 2px 5px rgba(0, 0, 0, 0.5);
			margin-bottom: 20px;
			margin-top: 0px;
			
		}
		.post-text {
			max-width: 100%;
			padding-left: 20px;
			padding-right: 20px;
			padding-top: 15px;
			text-overflow: wrap;
		}
		 .affix {
			top:400;
			width: 23%;
			z-index: 2 !important;
		}
		.menu2 {
			position: fixed;
			max-width: 100%;
			width: 220px;
			margin: 0 auto;
			/*right: -220px;*/
			z-index: -0;
		}
		.menu1 > li:nth-child(1), .menu2 > li:nth-child(1) {
			background-color: #96ceb4 !important;
			color: #fff;
			font-size: 1.3em;
		}
		.post-footer {
			position: relative;
			width: 100%;
			height: 40px;
			background-color: #ccc;
			margin-bottom: -20px;
		}
		.tag {
			position: relative;
			width: 25%; /*283px;*/
			padding-top: 8px;
			padding-bottom: 8px;
			text-align: center;
			/*padding: 10px 130px;*/
			background-color: #F7F7F7;
			color:#a3a3a3;
			font-size: 1.8em;
			font-family: 'fontAwesome', helvetica, arial;
		}
		.tag:hover {
			background-color: #96ceb4;
			color: #fff;
			cursor: pointer;
		}
		.tag:active {
			background-color: #b2b2b2;/* d5e4e6;*/
			color: #fff;
		}
		.tag:nth-child(2) {
			border-left: thin solid #ccc;
			border-right: thin solid #ccc;
			margin-left: -4px;
			margin-right: -4px;
		}
		.tag:last-child {
			border-left: thin solid #ccc;
			margin-left: -4px;
		}
		.tag-text {
			font-size: 0.60em;
		}
		.notifications {
			position: relative;
			max-width: 100%;
			top: -8px;
			left: -5px;
			background-color: #c83349;
			color: #fff;
			padding: 3px 6px;
			border-radius: 50px;
		}
		.notifications:hover {
			color: #c83349;
			background-color: #fff;
		}
		.layer {
			position: fixed;
			top: 0;
			max-width: 100%;
			margin: 0 auto;
			width: 100%;
			height: 100%;
			background-color: rgba(0, 0, 0, 0.6);
			z-index: 6;
			display: none;
		}
		.loader {
			font-size: 12em;
			margin-top: 200px;
			color: #fff;
		}
		#load {
			color: #fff;
		}
		.wall-image:hover {
			cursor: pointer;
		}
		.closeImg {
			position: fixed;
			top: 5px;
			right: 10px; /*270px;*/
			max-width: 100%;
			margin: 0 auto;
			font-size: 2em;
			color: #fff;
			cursor: pointer;
			z-index: 6;
			display: none;
		}
		ul.message-list { /*control message dropdown list maximum height*/
		    padding: 0;
		    max-height: 200px;
		    overflow-x: hidden;
		    overflow-y: auto;
		}
		ul > li.message-preview {
		    width: 275px;
		    border-bottom: 1px solid rgba(0, 0, 0, .15);
		}
		li.message-preview > a {
		    padding-top: 15px;
		    padding-bottom: 15px;
		}
		li.message-preview > a:hover {
			background-color: #E1E8ED;
		}
		.message-body { /*show ellipsis... when sentence overlap/longer */
		  max-width: 200px;
		  overflow: hidden;
		  text-overflow: ellipsis;
		  white-space: nowrap;
		  word-wrap: break-word;
		}
		.media-heading {
		  max-width: 200px;
		  overflow: hidden;
		  text-overflow: ellipsis;
		  white-space: nowrap;
		  word-wrap: break-word;
		}
		.media-object {
			width: 25px;
		}
		.publish-box {
			max-width: 800px;
			margin: 0 auto;
			background-color: #fff;/* #dfdfdf;*/
			padding: 3px 4px;
			margin-bottom: 20px;
			box-shadow: 1px 2px 6px rgba(0, 0, 0, 0.2);
		}
		.publish-box > a > img {
			position: absolute;
			top: 8px;
			max-width: 100%;
			width: 70px;
			padding-left: 5px;
		}
		#publish-data {
			position: relative;
			/*left: 50px;*/
			max-width: 100% !important; /*--------------------------------------------------------------------------------------------------------------------*/
			width: 80%;
			height: 100px;
			margin: 0 auto;
			resize: none;
			margin-left: 70px;
			/*margin-top: -40px;*/
			/*margin-bottom: 10px;*/
			background-color: #fff !important;
			/*background: url('profile/img_avatar_female.png');
			background-size: 70px;
			background-repeat: no-repeat;
			background-position: left 2px top 8px;*/
			border: none;
			border-radius: 3px;
			/*border-top: thin solid #d0d0d0;
			border-bottom: thin solid #d0d0d0;*/
			padding: 5px 0 0 10px;
			line-height: 1.5;
		}

		.post-attachments {
			position: relative;
			/*top: -38px;*/
			max-width: 100% !important;
			height: auto;
			background-color: #f5f5f5;
			border-radius: 3px;
			z-index: 1;
			/*border: thin solid #d0d0d0;*/
			/*padding: 5px;*/
			/*margin-bottom: 20px;*/
		}
		.post-attachments > .btn {
			position: relative;
			top: -3px;
			right: -50px;
		}
		.post-attachments > .btn-default {
			right: -50px;
		}
		.attachment-link {
			width: 50px;
			/*margin-left: -4px;*/
			background-color: #f5f5f5;
			color: #999;
			font-size: 1.5em;
			text-align: center;
			padding: 8px 50px 8px 30px;
			border: thin solid #eee;
		}
		.attachment-link:hover {
			background-color: #96ceb4; /*#ccc;*/
			color: #fff;
			border-left: thin solid #d0d0d0;
			border-right: thin solid #d0d0d0;
			cursor: pointer;
		}
		@media all and (max-width: 768px) { /*when screen size shrinks small*/
			.publish-box > textarea {
				background-size: 50px;
				padding: 5px 0 0 60px;
			}
			.post-attachments {
				max-width: 100% !important;
			}
			.publish-box > textarea {
				max-width: 100% !important;
			}
			.post-attachments > .btn {
				position: relative;
				top: -3px;
				right: -10px;
			}
			.post-attachments > .btn-default {
				right: -10px;
			}
		}
		.activities {
			max-width: 100%;
			max-height: 384px;
			overflow-y: auto;
		}
		.activity {
			border-bottom: thin solid #ccc;
			background-color: #f5f5f5;
			margin-bottom: 0px;
			padding: 8px 8px;
			font-family: helvetica, arial;
			font-size: 12px;
		}
		.activity > a {
			text-decoration: none;
			color: #444;
		}
		.activity:hover {
			background-color: #eee;
		}
		.activity > a > .media > .media-body> .media-heading {
			font-weight: bold;
		}
		.activity > a > .media > .media-object {
			float: left;
			width: 30px;
			margin-right: 5px;
		}
		.fa-bars {
			position: absolute;
			left: -36px;
			background-color: #96ceb4;
			color: #fff;
			padding: 5px 10px;
			cursor: pointer;
			display: none;
			box-shadow: -2px 2px 6px rgba(0, 0, 0, 0.3);
		}
		.fa-close {
			position: absolute;
			top: 13px;
			right: -17px;
			color: #777; /*#96ceb4;*/
			cursor: pointer;
			font-size: 22px;
		}
		.itemSlider {
			position: absolute;
			top: 0px;
			max-width: 95%;
			width: 792px; /*792px;*/
			max-height: 100px; /*80px;*/
			overflow-y: auto;
			margin: 0 auto;
			background-color: rgba(150, 206, 180, 0.6); /*#96ceb4;*/
			display: none;
			padding: 5px;
			z-index: 1;
		}
		.emoticon:hover {
			text-decoration: none;
		}
		.checkin {
			margin-top: 10px;
		}
		.checkin-update {
			width: 100%;
			height: auto;
			margin: 0 auto;
			border: thin solid #d0d0d0;
			background-color: #f5f5f5;
			padding: 10px;
			margin-bottom: 10px;
		}
		.checkin-update > a {
			text-decoration: none;
			color: #b2b2b2;
		}
		.checkin-update > a:hover {
			text-decoration: none;
			color: #92a8d1;
		}
		.fa-picture-o {
			max-width: 50%;
			margin: 0 auto;
			font-size: 12em;
		}
		/*overlay menu settings*/
		.overlay-menu {
			position: fixed;
			top: 0px;
			left: -300px; /*250px;*/
			width: 300px;
			height: 100%;
			overflow-y: auto;
			z-index: 6;
			background-color: rgba(150, 206, 180, 0.6);  /*rgba(146, 168, 209, 0.8); *//*#92a8d1;*/
			color: #777;
		}
		.overlay-menu > .user-profile {
			/*background: url('backgrounds/img17.jpg');
			background-size: 250px;
			background-repeat: no-repeat;
			background-position: left top -80px;*/
			background: -webkit-linear-gradient(90deg, #96ceb4 0%, #d9ecd0 70%); /*background-color: #f5f5f5;*/
			padding: 5px;
			border-bottom: thin solid #5b9aa0;
		}
		.profile-image {
			position: relative;
			width: 50px;
			margin-left: 6px;
		}
		.profile-username {
			position: relative;
			top: -5px;
			left: 5px;
			color: #444;
			font-weight: lighter;
		}
		.profile-email {
			position: relative;
			top: -20px;
			left: 100px;
			color: #777;
		}
		.close-menuSlider {
			padding: 10px 15px;
			/*background-color: #f5f5f5; /*-webkit-linear-gradient(90deg, #d9ecd0 10%, #96ceb4 70%); -color: #f5f5f5;*/
			/*box-shadow: inset 1px 2px 6px rgba(0, 0, 0, 0.4);*/
			color: #555;
			margin-left: -8px;
			border-radius: 50px;
			font-size: 1.3em;
		}
		.close-menuSlider:hover {
			/*padding: 10px 15px;*/
			background-color: #5b9aa0; /*#92a8d1;*/
			color: #fff;
			box-shadow: inset 1px 2px 6px rgba(255, 255, 255, 0.4);
			cursor: pointer;
			/*border-radius: 50px;*/
		}
		.show-menuSlider {
			position: fixed;
			top: 8px;
			left: -4px;
			font-size: 2em;
			background-color: rgba(150, 206, 180, 0.7); /*rgba(146, 168, 209, 0.8);*/ /*#92a8d1;*/
			color: #fff;
			box-shadow: 1px 3px 6px rgba(0, 0, 0, 0.5);
			padding: 5px 8px;
			border-radius: 5px;
			z-index: 6;
		}
		.show-menuSlider:hover {
			color: #55ACEE;
			cursor: pointer;
		}
		.show-menuSlider:active {
			color: #405d27;
		}
		.overlay-menu-content > ul > li:hover {
			background-color: #d5e4e6;
			color: #5b9aa0;
			cursor: pointer;
		}
		#overlay-subMenu > li {
			background-color: #d6d4e0; /*#b8a9c9;*/
		}
		#overlay-subMenu > li:hover {
			background-color: #b8a9c9;
		}
		#overlay-subMenu > li > a {
			text-decoration: none;
			color: #5b9aa0;
		}
		#overlay-subMenu > li > a:hover {
			color: #fff;
		}
		.errorHighlight {
			border: thin solid red !important;
			transition: border 500ms ease-in;
		}
		.page-scroller {
			position: fixed;
			top: 670px;
			background: rgba(0, 0, 0, 0.5);
			color: #fff;
			font-size: 2em;
			padding: 5px 15px;
			border-radius: 5px;
			cursor: pointer;
			z-index: 999;
		}
		.page-scroller:hover {
			color: #80ced6;
		}
		.page-scroller:active {
			color: #f2ae72;
		}
		.navbar-search {
			width: 250px !important;
		} 
	</style>

	<script type="text/javascript">
		$(function() {
			$('.notify').hide(); //hide notifications by default
			$('.post-load').hide();
			$('.page-scroller').hide();
			// $('.fa-bars').hide(); //hide the humburger-menu bars by default
			$('<audio id="alertNotify"><source src="backgrounds/messageNotify.mp3" type="audio/mp3"></audio>').appendTo('body');
			$('<audio id="msgNotify"><source src="backgrounds/alert_notify.mp3" type="audio/mp3"></audio>').appendTo('body');

			$('.user-profile-img').on('mouseenter',function() {
				$('.upload').stop(true,true).fadeIn('slow');
				$('.user-profile-img').on('mouseleave',function(){
					$('.upload').fadeOut('slow');
				});
			});
			$('.background-img').on('mouseenter',function() {
				$('.upload-bg').stop(true,true).fadeIn('slow');
				$('.background-img').on('mouseleave',function() {
					$('.upload-bg').fadeOut('slow');
				});
			});

			$('.user-profile-img').children('img').addClass('animated pulse');
			$('.menu1').addClass('animated bounceInLeft');
			$('.menu2').addClass('animated bounceInRight');

			$('.post-item-card').each(function() {
				$('.post-item-card').addClass('animated slideInUp');
			});

			/*$('.notify > span').each(function() { //show or hide notifications.
				// alert($('sup:eq(1)').find('.countInbox').html());
				var thisValue = $(this).find('span').html(); //get notification value
				if(thisValue <= '0') {
					$(this).hide();
				}
				else {
					//$(this).delay(2000).fadeIn('fast').addClass('notifications animated flash'); //notification styling and animation
					setTimeout(function() {
						$('#alertNotify')[0].play(); //delay for 2200sec before playing a sound.
					}, 2200);

					if($(this).parent().find('.fa').hasClass('fa-envelope-o')) {
						$(this).parent().find('.fa-envelope-o').addClass('fa-envelope').removeClass('fa-envelope-o');
					} else {
						$(this).parent().find('.fa-bell-o').addClass('fa-bell').removeClass('fa-bell-o');
					}
				}
			});*/

			/*if($('.countInbox').html() <= 0) {
				$('.countInbox').parent().hide();
			} else {
				$('.countInbox').parent().fadeIn('slow').addClass('notifications animated flash');
			}*/

			$('.closeImg').click(function() {
				$(this).hide(); //hide image closing button
				$('.layer').addClass('animated zoomOut').delay(1000).fadeOut(); //delay(200).fadeOut('slow');
				// $('.layer').removeClass('animated zoomOut');
			});
			
			$('.fa-bars').on('click', function() { //show hidden menu
				$('.menu2').animate({'right':'15px'}, 500).addClass('animated bounceInRight');
				$('.fa-bars').fadeOut('slow');
				$('.fa-close').show();
			});
			$('.fa-close').on('click', function() { //hide menu
				$('.menu2').animate({'right':'-220px'}, 500).removeClass('animated bounceInRight');
				$('.fa-close').hide();
				$('.fa-bars').fadeIn('slow');
			});

			//control the overlay-slider menu
			$('.show-menuSlider').on('click', function() {
				$('.navbar-search').val(""); //empty value from navbar search field.
				$('.layer').removeClass('animated zoomOut');
				$('.layer').html(''); //clear any data on layer
				$('.overlay-menu').animate({'left':'0px'}, 500); //.addClass('animated bounceInLeft');
				$('.layer').fadeIn('slow');
			});/*--------------------------------- to close the menu slider ---------------------------*/
			$('.close-menuSlider').on('click', function() {
				$('.overlay-menu').animate({'left':'-300px'}, 800); //.removeClass('animated bounceInLeft');
				$('.layer').fadeOut('slow');
			});
			//control sliding up/down of submenu from an overlay-menu
			$(".overlay-toggle-submenu").click(function() {
				// $("#overlay-subMenu").slideToggle();
				if($(this).find('ul').hasClass('collapse')) {
					$(this).find('ul').addClass('expand').removeClass('collapse').slideDown('slow');
				}
				else {
					$(this).find('ul').removeClass('expand').addClass('collapse').slideUp('slow');
				}
				/*if($(".overlay-subMenu").hasClass('collapse')) {
					$(".overlay-subMenu").addClass('expand').removeClass('collapse').slideDown('slow');
				}
				else {
					$(".overlay-subMenu").removeClass('expand').addClass('collapse').slideUp('slow');
				}*/
			});

			$('#photo, #attach, #smileys, #checkin').click(function() {
				$('.itemSlider').slideToggle('slow').toggleClass('animated pulse');
				var url ='', queryString = '', dataHandler='.itemSlider';

					if($(this).data('url') == "smiley") {
						url = $(this).data('url')+'s.php';
						queryString = 'smiley=true';
					}
					else if($(this).data('url') == "checkin") {
						url = $(this).data('url')+'.php';
						var userId = "<?php ($loggedon) ? print urlencode($userData->id) : print null; ?>";
						var profImg = "<?php ($loggedon) ? print urlencode($userData->imageUrl) : print null; ?>";
						var profName = "<?php ($loggedon) ? print urlencode($userData->username) : print null; ?>";
						queryString = 'checkin=true&uid='+userId+"&profileImage="+profImg+"&profileName="+profName;
					}
					else if($(this).data('url') == "attach") {
						url = $(this).data('url')+'.php';
						queryString = 'attach=true';
					}
					else {
						url = $(this).data('url')+'.php';
						var userId = $(this).data('uid');
						var profImg = "<?php ($loggedon) ? print urlencode($userData->imageUrl) : print null; ?>";
						var profName = "<?php ($loggedon) ? print urlencode($userData->username) : print null; ?>";
						queryString = 'photo=true&uid='+userId+"&profileImage="+profImg+"&profileName="+profName;
					}
					ajaxQueryLoad(url, queryString, dataHandler);
					// ajaxQueryLoad(url=$(this).data('url')+'.php', queryString='smiley=true', dataHandler='.testSlide');
			});

			$('.searchBtn').click(function(e) { //this query handles the search feature
				e.preventDefault();
				if($('.overlay-search').val() != "") {
					$('.navbar-search').val("");
					var searchTerm = $('.overlay-search').val().trim();
				}
				if($('.navbar-search').val() != "") {
					$('.overlay-search').val("");
					var searchTerm = $('.navbar-search').val().trim();
				}
				var url = "controller.php";
				var queryString = {"search":"true","q":searchTerm};
				var dataHandler = ".posts";
				if(searchTerm=="") {
					return false;
				} else {
					ajaxQueryLoad(url, queryString, dataHandler);
				}
			});

			$('#publish-post').attr('disabled', true);

			$(":reset").click(function() {
				var cancel = confirm('Cancel publishing your post?');
		      	if(cancel===false) {
		        	return false;
		      	} else {
					$('#publish-data').val('');
					$('#checkinPlace').val('');
				}
			});

			$('#publish-data').click(function() {
				$("#publish-data").select(); //select all text in textarea when click
			});

			$('#publish-data').on('keyup',function() {
				if($('#publish-data').val() != "") {
					$('#publish-data').removeClass('errorHighlight');
				}
				if($('#publish-data').val() == "" || $('#publish-data').val() == " ") {
					$('#publish-post').attr('disabled', true);
				} else {
					$('#publish-post').attr('disabled', false);
				}
			});

			$('#publish-post').click(function() {
				var dataString = $('#publish-data').val().trim(); //"publish=true";
				var userID = <?php ($loggedon) ? print $userData->id : print "null"; ?>;
				var profileImg = "<?php ($loggedon) ? print $userData->imageUrl : print null; ?>";
				var profileUsername = "<?php ($loggedon) ? print urlencode($userData->username) : print null; ?>";
				if(dataString == "") {
					$('#publish-data').addClass('errorHighlight');
					return false;
				}
				var queryString = {'publish':'true', 'publish_data':dataString, 'userId':userID, 'profImage':profileImg, 'profileName':profileUsername};
				$.ajax({
					url: 'controller.php',
					type: 'POST',
					data: queryString,
					cache: false,
					beforeSend: function() {
						$("<div class='post-item-card'></div>").html('<center>Publishing post...</center>').prependTo('.posts');
					},
					success: function(data) {
						var jObjData = JSON.parse(data);
						$('.post-item-card:first').html(jObjData['published']);
						$('#publish-data').val(""); //erase data from field
					},
					error: function() {
						alert('Error 404: url not found!');
					}
				});
			});

		}); /*document body ends*/

		function ajaxQueryLoad(url, queryString, dataHandler) {
			$.ajax({
				url: url,
				type: 'GET',
				data: queryString,
				cache: false,
				beforeSend: function() {
					$(dataHandler).html('');
					$(dataHandler).html('<center><span class="fa fa-refresh fa-pulse fa-5x"></span></center>');
				},
				success: function(responseData) {
					$(dataHandler).html(responseData);
				},
				error: function() {
					$(dataHandler).html('<p style="color:red;font-size:1.8em;">Error 404: Url not found!.</p>');
				}
			});
		}
	</script>
	
</head>
<body id="top" data-spy="scroll" data-target=".menu1 .publish-box" data-offset="50">
<?php if($loggedon==true) { ?><span id="counterTimer">10</span><?php } //if user logged in start time counter ?>
<div class="layer"></div>
<span class="closeImg" title="Close"><span class="fa fa-times-circle"></span></span>
<!-- Menu slider visible on small screens -->
<div class="show-menuSlider animated bounceInLeft hidden-sm hidden-md hidden-lg fa fa-th-large" title="Show Menu"></div>
<!-- overlay side-bar Menu -->
<div class="overlay-menu">
	<div class="user-profile">
		<span class="fa fa-arrow-left close-menuSlider" title="Close"></span>
		<!-- <img src="profile/img_avatar_male.png" class="profile-image img-circle" /> -->
		<?php if($loggedon==true) { ?>
		<img src="<?php echo $userData->imageUrl; ?>" class="profile-image img-circle" />
		<span class="profile-username"><?php echo $userData->username; ?> <br><small class="profile-email"><?php echo $userData->email; ?></small></span>
		<?php } else { ?>
			<img src="profile/avatars/avatar_192px.png" class="profile-image img-circle" />
			<!-- <span class="profile-username"> <br><small class="profile-email"></small></span> -->
		<?php } ?>
	</div>
	<div class="overlay-menu-content">
	<?php if($loggedon==true) { ?>
		<ul class="list-group">
			<li class="list-group-item">
				<form role="form" action="" method="GET" enctype="application/x-www-urlencoded">
		            <div class="input-group">
		          		<input type="search" autosave="autosave" results="5" name="q" class="form-control overlay-search" placeholder="search...">
		          		<div class="input-group-btn">
		          		  <button type="submit" class="btn btn-default searchBtn" onclick="searchContent();"><span class="fa fa-search"></span></button>
		          		</div>
	          		</div>
				</form>
			</li>
			<li class="list-group-item overlay-toggle-submenu">
				<span class="fa fa-envelope"></span> Messages <span class="badge"><span class="countInbox"></span></span>
				<ul class="list-group collapse overlay-subMenu" id="overlay-subMenu">
				<li class="list-group-item">
					<a href="javascript:getMessageBox('unreadMsg');"><span class="fa fa-envelope-open"></span> View Inbox</a> (<span class="countInbox"></span>)
				</li>
				<li class="list-group-item"><a href="javascript:writeMsg();" class="createMsg"><span class="glyphicon glyphicon-pencil"></span> Write message</a></li>
				</ul>
			</li>
			<li class="list-group-item"><span class="fa fa-bell"></span> Notifications <span class="badge alerts"></span></li>
			<li class="list-group-item" onclick="showInviteList()"><span class="fa fa-user-plus"></span> Invitations <span class="badge"><span class="friendinvites"></span></span></li>
			<li class="list-group-item" onclick="inviteFilter()"><span class="fa fa-users"></span> Friends <span class="badge friends"><?php echo $groups; ?></span></li>
			<li class="list-group-item"><span class="fa fa-map-marker"></span> &nbsp;&nbsp;Places <span class="badge checkins"><?php echo $places; ?></span></li>
			<li class="list-group-item overlay-toggle-submenu" id="overlay-toggle-submenu">
			<!-- <a href="#collapseThree" data-toggle="collapse" data-parent="#accordion"> -->
				<span class="fa fa-briefcase"></span> Account Settings <span class="fa fa-chevron-right pull-right"></span>
			<!-- </a> -->
				<!-- <div id="collapseThree" class="panel-collapse collapse"> -->
					<ul class="list-group collapse overlay-subMenu" id="overlay-subMenu">
						<li class="list-group-item"><a href="javascript:getUserProfile()"><span class="glyphicon glyphicon-user"></span> Profile Account</a></li>
						<li class="list-group-item"><a href="javascript:manageAccount()"><span class="glyphicon glyphicon-cog"></span> Settings</a></li>
						<li class="list-group-item"><a href="#help"><span class="fa fa-question-circle"></span> Help</a></li>
						<li class="list-group-item"><a href="signout/true"><span class="fa fa-power-off"></span> Logout</a></li>
					</ul>
				<!-- </div> -->
			</li>
		</ul>
	<?php } else { ?>
		<ul class="list-group">
			<li class="list-group-item"><a href="signup"><span class="fa fa-user-plus"></span> Signup</a></li>
			<li class="list-group-item"><a href="login"><span class="fa fa-sign-in"></span> Login</a></li>
		</ul>
	<?php } ?>
	</div>
</div><!-- overlay-Menu END -->


	<div class="bgCover">
		<div class="background-img">
			 <!-- height="300px" width="100%"  width="100%" -->
			<?php if($loggedon==true) { ?>
			<img src="<?php echo $userData->urlpath; ?>" />
			<a href="javascript:void(0)" data-url="backgrounds" data-whatever="<?php echo $userData->email; ?>" class="pop-upload"><span class="upload-bg" id="upload-bg" title="Change background image"><span class="fa fa-pencil"></span></span></a>
			<?php } else { ?>
				<img src="backgrounds/background.jpg" />
			<?php } ?>
		</div>
		<!-- NAVIGATION -->
		<nav class="navbar navbar-inverse">
		    <div class="navbar-header">
		    	<?php if($loggedon==false) { #if not logged in, then show ?>
		        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
		          <span class="icon-bar"></span>
		          <span class="icon-bar"></span>
		          <span class="icon-bar"></span>                        
		      	</button>
		      	<?php } ?>
		      	<a class="navbar-brand hidden-xs hidden-sm" href="home">SiteName</a>
		    </div>
		    <div>
		      <div class="collapse navbar-collapse" id="myNavbar">
	            <form role="form" action="" method="GET" enctype="application/x-www-urlencoded" class="navbar-form navbar-left visible-xs visible-md visible-lg">
		            <div class="input-group">
		          		<input type="search" autosave="autosave" results="5" size="40" name="q" class="form-control navbar-search" placeholder="search...">
		          		<div class="input-group-btn">
		          		  <button type="submit" class="btn btn-default searchBtn" onclick="searchContent();"><span class="fa fa-search"></span> Search</button>
		          		</div>
	          		</div>
				</form>
		        <ul class="nav navbar-nav navbar-right">
		        <?php if($loggedon==false) { ?>
		        	<li><a href="signup"><span class="fa fa-user-plus"></span> Signup</a></li>
		        	<li><a href="login"><span class="fa fa-sign-in"></span> Login</a></li>
		        	<!-- <li><a href="javascript:testsmallWindow()">Test</a></li> -->
		        <?php } else { ?>
		        <li class="hidden-sm"><a href="javascript:getUserProfile()"><img src="<?php echo $userData->imageUrl; ?>" width="18px"/> <?php echo $userData->firstname; ?></a></li>
		          <li class="dropdown">
		          	<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><span class="fa fa-envelope-o"></span> 
		          		<span class="hidden-xs">Messages</span><sup class="notify"><span class="countInbox"></span></sup><i class="caret"></i>
		          	</a>
		          	<!-- retrieve a list of messages via jqueryAjax -->
		          	<ul class="dropdown-menu message-list"></ul>
		          </li>
		          <li><a href="#alerts"><span class="fa fa-bell-o"></span> <span class="hidden-xs">Notifications</span><sup class="notify"><span class="alerts"></span></sup></a></li>
		          <li>  <!-- javascript:inviteFilter() -->
		          		<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><span class="fa fa-user-plus"></span> 
		          		<span class="hidden-xs">invites</span></span><sup class="notify"><span class="friendinvites"></span></sup> <span class="fa fa-caret-down"></a>
		          		<ul class="dropdown-menu invites">
			          		<!-- <li><a href="javascript:inviteFilter()"><span class="fa fa-users"></span> Manage Friends</a></li>
		          			<li class="divider"></li> -->
		          		</ul>
		          </li>
		          <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="javascript:void(0)">
		          <span class="fa fa-cogs"></span> <span class="hidden-xs">Configure</span> <span class="caret"></span></a>
		            <ul class="dropdown-menu">
		              <!-- <li><a href="javascript:writeMsg()" class="createMsg"><span class="fa fa-edit"></span> Write Inbox</a></li> -->
					  <li><a href="javascript:getUserProfile()"><span class="glyphicon glyphicon-user"></span> Profile</a></li>
		              <li><a href="javascript:manageAccount()"><span class="fa fa-cog"></span> Settings</a></li>
		              <li><a href="#help"><span class="fa fa-question-circle"></span> Help</a></li>
		              <li class="divider"></li>
		              <li><a href="signout/true"><span class="fa fa-power-off"></span> Logout</a></li> <!-- controller.php?signout=true -->
		            </ul>
		          </li>
		          <?php } //end else ?>
		        </ul>

		      </div>
		    </div>
		</nav>
	</div><!-- bgCover END -->
	<!-- user profile image -->
	<div class="user-profile-img">
		<?php if($loggedon==true) { ?>
		<img src="<?php echo $userData->imageUrl; ?>" class="img-responsive" />
		<a href="javascript:void(0)" data-url="profile" data-whatever="<?php echo $userData->email; ?>" class="pop-upload"><span class="upload" id="upload" title="Change profile picture"><span class="fa fa-camera"></span></span></a>
		<?php } ?>
	</div>

	<div class="container-fluid bg-3">
		<div class="user-content">
			<div class="row">
				<div class="col-md-3">
					<!-- Menu One column -->
					<ul class="list-group menu1 hidden-xs hidden-sm" data-spy="affix" data-offset-top="50">
						<li class="list-group-item"><center>Menu</center></li> <?php #echo $messages; ?>
						<li class="list-group-item"><span class="fa fa-envelope"></span> Messages <span class="badge"><span class="countInbox"></span></span></li>
						<li class="list-group-item"><span class="fa fa-bell"></span> Notifications <span class="badge alerts"></span></li>
						<li class="list-group-item"><span class="fa fa-user-plus"></span> Invitations <span class="badge"><span class="friendinvites"></span></span></li>
						<li class="list-group-item"><span class="fa fa-users"></span> Friends <span class="badge friends"></span></li>
						<li class="list-group-item"><span class="fa fa-map-marker"></span> &nbsp;&nbsp;Places <span class="badge checkins"></span></li>
						<?php if($loggedon==true) { ?>
						<li class="list-group-item" onclick="manageAccount()"><span class="fa fa-briefcase"></span> Account Settings <span class="fa fa-chevron-right pull-right"></span></li>
						<?php } else { ?>
						<li class="list-group-item"><span class="fa fa-briefcase"></span> Account Settings <span class="fa fa-chevron-right pull-right"></span></li>
						<?php } ?>
					</ul>
				</div>
				<div class="col-md-7">
				<?php if($loggedon==true) { ?>
					<!-- Posting blog item -->
					<div class="publish-box">
						<a href="javascript:getUserProfile()"><img src="<?php echo $userData->imageUrl; ?>" /></a>
						<textarea id="publish-data" placeholder="share what's on your mind&hellip;" autofocus></textarea>
						<div class="itemSlider">
							
						</div>
						<div class="post-attachments">
							<a href="javascript:void(0)" id="photo" data-url="post_image" data-uid="<?php echo $userData->id; ?>"><span class="attachment-link fa fa-camera"></span></a>
							<a href="javascript:void(0)" id="attach" data-url="attach"><span class="attachment-link fa fa-paperclip"></span></a>
							<a href="javascript:void(0)" id="smileys" data-url="smiley"><span class="attachment-link fa fa-smile-o"></span></a>
							<a href="javascript:void(0)" id="checkin" data-url="checkin"><span class="attachment-link fa fa-map-marker"></span></a>
							<button type="reset" class="btn btn-default" ><span class="fa fa-remove"></span> Cancel</button>
							<button type="button" class="btn btn-primary"  id="publish-post"><span class="fa fa-globe"></span> Publish</button>
						</div>
					</div>
				<?php } ?>
					<!-- Post Items column -->
					<div class="posts"></div>
					<center><div class="fa fa-spinner fa-pulse fa-4x post-load"></div></center>
				</div><!-- col-md END -->
				<div class="col-md-2">
					<!-- Menu Two column -->
					<ul class="list-group menu2 hidden-xs">
						<li class="list-group-item "><span class="fa fa-bars"></span> <span>Recent Activities</span> <span title="Close" class="fa fa-close"></span></li>
						<li class="activities list-group-item">
							<!-- <div class="activity">
								<a href="#activity1">
									<div class="media">
										<img src="profile/img_avatar_male.png" class="media-object">
										<div class="media-body">
											<span class="media-heading">Claudia Bernard</span>
											<span>checked in - at <span class="fa fa-map-marker text-info"></span> Google HQ Mountain view, CA.</span>
										</div>
									</div>
								</a>
							</div>-->
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div><a href="#top" class="page-scroller" title="To Top"><span class="fa fa-chevron-up"></span></a></div>
	</div><!-- container-fluid END -->
	
	<script type="text/javascript"> /*margin-top:300px;*/
		//script calling modal window and ajax update getData
		$('.pop-upload').on('click', function(event) { //handles popup the window element to upload images
			event.preventDefault(); //prevent to load normally
			$('body').children('.layer').removeClass('animated zoomOut');
			var triggeredBtn = $(this); // Button that triggered the modal
		    var recipient = triggeredBtn.data('whatever'); // Extract info from data-* attributes
		    var sender = triggeredBtn.data('url');  //using link and a buttons to trigger one modal and extract info.
		    var dataString = 'email=' + recipient +'&sender='+sender;
		    //var path = sender + '.php'; //using two buttons to trigger one modal
		    $.ajax({
		        type: "GET",
		        url: "upfile.php",
		        data: dataString,
		        cache: false,
		        beforeSend: function() {
		        	$('.layer').html('<center><span class="fa fa-spinner fa-pulse loader"></span><h3 id="load">Loading&hellip;</h3></center>').show();
		        },
		        success: function(data) {
		            $('.layer').html(data).show();
		        }
		    });
		});

		function writeMsg(internalRecipient) {
			$('.overlay-menu').animate({'left':'-300px'}, 800); //hide drawable-sidebar-menu if open
			$('.layer').removeClass('animated zoomOut');
			var dataString = {"writeMessage":"true", "senderEmail": "<?php ($loggedon) ? print $userData->email : print null; ?>", "senderNames": "<?php ($loggedon) ? print $userData->username : print null; ?>", "secret_recipient":internalRecipient };
			$.ajax({
				type: "GET",
				url: "createInbox.php",
				data: dataString,
				cache: false,
				beforeSend: function() {
					$('.layer').html('');
				},
				success: function(loadform) {
					$('.layer').html(loadform).show();
				}
			});
		}
		function getMessageBox(thisBox) { //get message box to manage all messages
			$('.overlay-menu').animate({'left':'-300px'}, 800); //hide drawable-sidebar-menu if open
			$('.layer').removeClass('animated zoomOut');
			$.ajax({
				type: "GET",
				url: "openInbox.php",
				data: {"messagebox":"true", "selectBox":thisBox}, //thisBox
				beforeSend: function() {
					$('.layer').html('');
				},
				success: function(loadform) {
					$('.layer').html(loadform).show();
				}
			});
		}
		function inboxNotify() {
			if($('.countInbox').html() <= 0) {
				$('.countInbox').parent('sup').hide().removeClass('notifications');
				$('.countInbox').closest('li').find('.fa-envelope').addClass('fa-envelope-o').removeClass('fa-envelope');

			} else if($('.countInbox').html() >= 1) {
				$('.countInbox').parent('sup').fadeIn('slow').addClass('notifications animated flash');
				/*setTimeout(function() {
					$('#alertNotify')[0].play(); //delay for 2200sec before playing a sound.
				}, 2200);*/
				if($('.countInbox').closest('li').find('.fa').hasClass('fa-envelope-o')) {
					$('.countInbox').closest('li').find('.fa-envelope-o').addClass('fa-envelope').removeClass('fa-envelope-o');
				}
			}
		}
		function invitesNotify() {
			if($('.friendinvites').html() <= 0) {
				$('.friendinvites').parent('sup').hide().removeClass('notifications');
			} else if($('.friendinvites').html() >= 1) {
				$('.friendinvites').parent('sup').fadeIn('slow').addClass('notifications animated flash');
			}
		}
		function updateInboxCount() { //get number of inbox Messages count them
			$.get("message_controller.php", {"countInbox":"true"}, function(data) {
				var jasondata = JSON.parse(data);
				var dataCount = jasondata.response;
				$('.countInbox').html(dataCount);
				inboxNotify(); //update notification number
			});
		}
		updateInboxCount(); //call function, on first load of window to show number of inbox Msgs
		function pushAlertsCount() { //call function to return all number of counted, invites,checkins,friends
			$.get("controller.php", {"countAlerts":"true"}, function(data) {
				var jasondata = JSON.parse(data);
				var countCheckins = jasondata.checkinsCount;
				var countInvites = jasondata.invitesCount;
				var countFriends = jasondata.friendsCount;
				var countAlerts = 0;
				$('.checkins').html(countCheckins); //update checkins number
				$('.friendinvites').html(countInvites); //update friends invites number
				$('.friends').html(countFriends); //update friendslist number
				$('.alerts').html(countAlerts);
			});
		}
		pushAlertsCount();
		//call function to invite friends GUI window popup
		function inviteFilter() {
			$('.overlay-menu').animate({'left':'-300px'}, 800);
			$('.layer').removeClass('animated zoomOut');
			$.get("filterusers.php", {"filter":"true"}, function(data) {
				$('.layer').html(data).show();
			});
		}
		//call function to load available invitation to navbar drop-down-list
		function getInvitations() {
			$.get("controller.php", {"getinvites":"true", "email":"<?php ($loggedon) ? print $userData->email : print null; ?>"}, function(data) {
				$('.invites').append('<li style="border-top:thin solid #eee;padding-top:5px;"><center>Checking invites...</center></li>');
				setTimeout(function() {
					$('.invites li:last').remove();
					$('.invites').html(data);
				}, 2000);
				invitesNotify(); //update popups after loading data immediately
			});
		}
		getInvitations(); //invoke function onPageLoad to retrieve all invitation alerts

		function showInviteList() {
			$('.overlay-menu').animate({'left':'-300px'}, 800);
			$('.layer').removeClass('animated zoomOut');
			$.get("invitation_requests.php", {"getInvitations":"true", "email":"<?php ($loggedon) ? print $userData->email : print null; ?>"}, function(data){
				$('.layer').html(data).show();
			});
		}

		function openInvite(eventdata) { //load invitation window
			$('.layer').removeClass('animated zoomOut');
			var namesdata = eventdata.dataset.names;
			var emaildata = eventdata.dataset.email;
			var urldata = eventdata.dataset.url;
			var timeStamp = eventdata.dataset.stamp;
			var ownerEmail = eventdata.dataset.owner;
			$.get("viewInvites.php", {"viewInvitations":"true", "profUrl":urldata, "names":namesdata, "email":emaildata, "dateStamp":timeStamp, "ownerEmail":ownerEmail}, function(data) {
				$('.layer').html(data).show();
			});
		}

		//get all wallposts
		var action = 'inactive';
		var limit = 5;
		var start = 0;
		function retrievePosts(start, limit) {
			/*$.get("controller.php", {"retrievePosts":"true"}, function(data) {
				if(data != "") { data = data } else { data = '<div class="post-item-card"><div class="post-text"><p><center>You have no post available!.</center></p></div></div>'; } //if no posts for authorized user, then show above message.
				$('<div class="post-item-card animated slideInUp"></div>').html('<center><span class="fa fa-spinner fa-pulse loader"></span><h3 id="load">Loading&hellip;</h3></center>').prependTo('.posts');
				setTimeout(function() {
					$('.posts').html(data);
				}, 2000);
			});*/
			$.ajax({
				url: 'controller.php',
				method: "GET",
				data: {"retrievePosts":"true", "limit":limit, "start":start},
				cache: false,
				beforeSend: function() {
					$('.post-load').show();
				},
				success: function(data) {
					$('.post-load').hide();
					if(data != "false") { 
						data = data;
						action = 'inactive';
						return $('.posts').append(data);
					}
					else if(data == false) { 
						data = '<div class="post-item-card"><div class="post-text"><p><center>You have no post available!.</center></p></div></div>';
						action = 'active';
						$('.posts').append(data);
						return false;
					} //if no posts for authorized user, then show above message.
					/*$('<div class="post-item-card animated slideInUp"></div>').html('<center><span class="fa fa-spinner fa-pulse loader"></span><h3 id="load">Loading&hellip;</h3></center>').prependTo('.posts');*/
					
					/*setTimeout(function() {
						$('.posts').html(data);
					}, 2000);*/
				}
			});
		}
		if(action == 'inactive') {
			action = 'active';
			retrievePosts(start, limit); //call/invoke function to display all posts
		}
		$(window).scroll(function() {
			if($(window).scrollTop() + $(window).height() > $('.posts').height() && action == 'inactive') {
				action = 'active';
				start = start + limit;
				$('.post-load').show();
				setTimeout(function() {
					retrievePosts(start, limit); //call/invoke function to display all posts
					<?php if($loggedon==true) {$user->updateUserBrowserActivity();} ?> //if user scrolls down add new time to session life
					$('.page-scroller').fadeIn('slow').addClass('animated rubberBand');
				}, 1000);
			}
		});

		//get all recent activities
		function retrieveActivities() {
			$.get("controller.php", {"retieveActivities":"true"}, function(data) {
				$('<div class="activity"></div>').html('<center>Loading...</center>').prependTo('.activities');
				setTimeout(function() {
					$('.activities').html(data);
				}, 2500);
			});
		}
		retrieveActivities(); //call/invoke function to fill sidebar activities

		function retrieveInboxMessages() {
			$.post("controller.php", {"retrieveInbox":"true", "email":"<?php ($loggedon) ? print $userData->email : print null; ?>"}, function(data) {
				$('.message-list').append('<li class="message-preview"><a href="javascript:void(0)"><center>Checking inbox...</center></a></li>');
				setTimeout(function() {
					$('.message-preview:last').remove(); //remove progress loading message
					$('.message-list').html(data); //.appendTo('.message-list');
				}, 3000);
			});
		}
		retrieveInboxMessages(); //call/invoke funtion to retrieve all unread inbox messages

		function manageAccount() {
			$('.overlay-menu').animate({'left':'-300px'}, 800);
			$('.layer').removeClass('animated zoomOut');
			$.get("account.php", {"manage":"true"}, function(data) {
				$('.layer').html('<center><span class="fa fa-spinner fa-pulse loader"></span></center>').show();
				setTimeout(function() {
					$('.layer').html(data).show();
				}, 200);
			});
		}

		function getUserProfile() {
			$('.overlay-menu').animate({'left':'-300px'}, 800);
			$('.layer').removeClass('animated zoomOut');
			$.get("profile.php", {"userprofile":"true","uid":"<?php ($loggedon) ? print $userData->id : print null; ?>",
				"firstname":"<?php ($loggedon) ? print $userData->firstname : print null; ?>",
				"lastname":"<?php ($loggedon) ? print $userData->lastname : print null; ?>",
				"email":"<?php ($loggedon) ? print $userData->email : print null; ?>", 
				"acc_status":"<?php ($loggedon) ? print $userData->status : print null; ?>",
				"urlpath":"<?php ($loggedon) ? print $userData->imageUrl : print null; ?>"}, function(data) {
				$('.layer').html(data).show();
			});
		}

		function getLoginActivity() { //check user browsing activity on pages, else log the user out, if unattended.
			$.get("controller.php", {"expire_logout":"true"}, function(data) {
				var jasondata = JSON.parse(data);
				if(jasondata.response == "true") {
					$('.layer').html('<div style="font-size:3em;color:#fff;text-align:center" ><span style="font-size:5em;margin-top:200px;" class="fa fa-lock"></span><br>Your Session has expired!<div>').show();
					setTimeout(function() {
						// return window.open('controller.php?signout=true','_self');
						return window.open('signout/true','_self');
					}, 5000);
				}
			});
		}

		function runDBCronJob() {
			$.get("controller.php",{"runcronjob":"true"}, function(data) {
				var jasondata = JSON.parse(data);
				// alert(jasondata.response);
			});
		}
		runDBCronJob();//run once after login
		
		function showImage(pathPhoto) {
			var imgUrlpath = pathPhoto;
			$('.layer').removeClass('animated zoomOut'); //remove class if was added before
			$.ajax({
				type: "GET",
				url: "controller.php",
				data: {"openImg":true, "urlImgPath":imgUrlpath},
				cache: true,
				beforeSend: function() {
					$('.layer').html('<center><span class="fa fa-spinner fa-pulse loader"></span><h3 id="load">Loading&hellip;</h3></center>').show();
				},
				success: function(dataResponse) {
					$('.layer').html(dataResponse).show(); //dataString
					$('.closeImg').delay(1000).fadeIn(); //show image closing button
				}
			});
		}

		function objectPush() { //push item popup messages
	        var i = document.getElementById('counterTimer');
	        if(i === null) {
	          return false;
	        }
	        if (parseInt(i.innerHTML) == 0) {
	           i.innerHTML = 10;
	           retrieveInboxMessages(); //get all inbox message list, populate the drop-down list
	           updateInboxCount(); //get all number of inbox messages available
			   getInvitations(); //get all list number of invitations available, push into a drop-down
			   invitesNotify(); //get all invites notification dispaly number in a popup
			   pushAlertsCount(); //updates notification number
			   getLoginActivity() //check if user is active on browsing or logout user
	           return 0;
	        }
	          i.innerHTML = parseInt(i.innerHTML) - 1;
	    }
	    setInterval(function(){ objectPush(); }, 1500);
		//handles the display of images on the wall zooms In&Out
		/*$('.wall-image').on('click', function() { 
			var imgUrlpath = $(this).attr('src');
			var dataString = '<div><center><img src='+imgUrlpath+' class="animated zoomIn" style="width:65%; margin-top:50px;"/></center></div>';
			$('.layer').removeClass('animated zoomOut'); //remove class if was added before
			$.ajax({
				cache: true,
				beforeSend: function() {
					$('.layer').html('<center><span class="fa fa-spinner fa-pulse"></span><h3 id="load">Loading&hellip;</h3></center>').show();
				},
				success: function() {
					$('.layer').html(dataString).show();
					$('.closeImg').delay(1000).fadeIn(); //show image closing button
				}
			});
		});	*/
		function testsmallWindow() {
			var width = 600;
			// var width = width / 2;
			var height = 750; //300;
			// var height = height / 2;
			var top = 0; //200;
			var left = 500;
			var options = "status=1, width="+width+",height="+height+",top="+top+",left="+left;
			window.open(document.location.href,"",options);
		}

		$("div a[href='#top']").on('click', function(event) {
	      // Prevent default anchor click behavior
	      event.preventDefault();
	      // Store hash
	      var hash = this.hash;
	      // Using jQuery's animate() method to add smooth page scroll
	      // The optional number (900) specifies the number of milliseconds it takes to scroll to the specified area
	      $('html, body').animate({
	        scrollTop: $(hash).offset().top
	      }, 2000, function() {
	        // Add hash (#) to URL when done scrolling (default click behavior)
	        window.location.hash = hash;
	      });
	    });
	</script>

</body>
</html>