<?php
//load all required files
require_once('bootstrap.php');


//control logging/sign-in requests
if(isset($_POST['login']) ){ //} && $_SERVER['REQUEST_METHOD'] == "POST") {
	// sleep(1);
	$data['email'] = htmlentities(stripslashes(strip_tags(trim($_POST['email']))));
	$data['password'] = htmlentities(stripslashes(strip_tags(trim($_POST['password']))));
	$data['remember'] = ($_POST['remember']=="on") ? htmlentities(stripslashes(strip_tags(trim($_POST['remember'])))) : "off";
	//get saved data to compare with login credentials
	$results = $user->login($data['email'], $data['password']);
	if($results != false) {
		if($results == "success") {
			//quickly process login form cookies
			if($data['remember'] == "on") {
				//if remember checkbox is checked, then setcookie
				setcookie("email", $data['email'], time() + (10 * 356 * 24 * 60 * 60));
                setcookie("password", $data['password'], time() + (10 * 356 * 24 * 60 * 60));
			} else {
				//else if remember was unchecked then, unset/erase cookie
				if(!empty($_COOKIE['email'])) { //unset email cookie
					setcookie("email", "", time() - (10 * 356 * 24 * 60 * 60));
				}
				if(!empty($_COOKIE['password'])) { //unset password cookie
	                setcookie("password", "", time() - (10 * 356 * 24 * 60 * 60));
                }
			}
			$response['response'] = "success";
			$response['location_url'] = "<script>window.open('welcome','_self');</script>"; #newwelcome.php
		}
		else {
			$response['response'] = "activation"; //if account not activated
		}
	}
	else {
		$response['response'] = "failed"; //if fails to login
	 	// echo json_encode($response);
	}
	echo json_encode($response);
	exit();
}
//control user logout requests
if(isset($_GET['signout'])) {
	$_SESSION['loggedOn'] = false;
	session_unset($_SESSION['authorize']);
	session_unset($_SESSION['active_time']);
	// session_destroy();
	header("Location: ../home"); #newwelcome.php
}
if(isset($_GET['expire_logout'])) { # 900sec -> 15min
	if( (time() - $_SESSION['active_time']) > 900) { //is time is older than 1 minute. time in seconds 60sec == 1min. 300sec is 5min
		//header("Location: controller.php?signout=true"); //else the user out
		$response['response'] = "true"; //if is yes is older then log user out.
		session_unset($_SESSION['active_time']);
	}
	else {
		$response['response'] = "false"; //else do nothing
	}
	echo json_encode($response);
	exit();
}
//control submission of user form data registering requests
if(isset($_POST['signup']) && $_SERVER['REQUEST_METHOD'] == "POST") {
	sleep(1);
	$data['firstname'] = stripcslashes(strip_tags(trim($_POST['firstname'])));
	$data['lastname'] = stripcslashes(strip_tags(trim($_POST['lastname'])));
	$data['email'] = stripcslashes(strip_tags(trim($_POST['email'])));
	$data['password'] = stripcslashes(strip_tags(trim($_POST['password'])));
	if($data['firstname'] != "" && $data['lastname'] != "" && $data['email'] != "" && $data['password'] != "") {
		if($user->signup($data['firstname'], $data['lastname'], $data['email'], $data['password'])) {
			$response['response'] = "success";
			// $response['location_url'] = "<script>window.open('newwelcome.php','_self');</script>";
			// echo json_encode($response);
			if(is_dir('temp') && file_exists("temp/activation.html")) { //if file available, then open it.
				$filename = "temp/activation.html"; //open dir temp to get the file.
				 echo "<script>window.open('".$filename."','_blank')</script>"; //automatically opens new mail, as temp file.
			}
		} else {
			$response['response'] = "failed";
		}
	}
	else {
		$response['response'] = "failed";
		// echo json_encode($response);
	}
	echo json_encode($response);
	exit();
}

//activate uer account after registration
if(isset($_GET['confirm'])) {
	$emailVerified = stripslashes(strip_tags(trim($_GET['verification']))); //this is email data
	$tokendata = stripslashes(strip_tags(trim($_GET['token']))); //this is token data generated
	if($user->accountActivation($emailVerified, $tokendata)) {
		header("Location: ../../../login");
	} else {
		$basepath = "../../../".basename(realpath(__DIR__))."/";
	    header("Location: ../".$basepath."errorpages/"); //temp/error404.html
	}
}

//password reset request
if(isset($_POST['requestReset'])) {
	sleep(1);
	$email = htmlentities(strip_tags(trim($_POST['email'])));
	if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
		if($user->requestPassword($email)) { //now call the function to process the reset request
			//open mail immediately
			if(is_dir('temp') && file_exists("temp/email.html")) { //if file available, then open it.
				$filename = "temp/email.html"; //open dir temp to get the file.
				#echo "<script>window.open('".$filename."','_blank')</script>"; //automatically opens new mail, as temp file to see password.
			}
			$jasonresponse['message'] = "success";
			$jasonresponse['response'] = "<span class='fa fa-info-circle animated rubberBand'></span> Successfully reset. Please check your email inbox";
			$jasonresponse['objfile'] = $filename; //try pass filename to jsonObject variable
		}
		else {
			$jasonresponse['message'] = "<span class='fa fa-warning animated flash'></span> Sorry failed to process your request!";
		}
	}
	else {
		$jasonresponse['message'] = "<span class='fa fa-warning animated flash'></span> Sorry your email address is invalid!";
	}
	echo json_encode($jasonresponse);
	exit();
}

//catch request from email event to process final password reset
if(isset($_GET['processReset'])) {
	$email = stripslashes(strip_tags(trim($_GET['email'])));
	$keydata = stripslashes(strip_tags(trim($_GET['token'])));
	if($user->resetPassword($email, $keydata)) {
		if(is_dir('temp') && file_exists("temp/email.html")) { //check i file exists
			// echo "<script>self.close()</script>";
			unlink("temp/email.html"); //now delete old temp file, useless once link clicked and password reset
		}
		if(is_dir('temp') && file_exists("temp/newpass.html")) { //if file available, then open it.
			$filename = "../../../temp/newpass.html"; //open dir temp to get the file.
			 echo "<script>window.open('".$filename."','_blank')</script>"; //automatically opens new mail, as temp file to see password.
		}
	}
	else {
		if(!is_dir('temp')) {
			mkdir('temp');
		}
		/*$fp = fopen("temp/error.html", 'w');
	    fwrite($fp, "<title>Request Error</title><h3>Error 404</h3><div>An Error occured processing your request.<br>The page you are looking for maybe expired or removed. Please try again later.</div>");
	    fclose($fp);*/
	    $basepath = "../../../".basename(realpath(__DIR__))."/";
	    header("Location: ../".$basepath."errorpages/"); //temp/error404.html
	}
	exit();
}

//control the checkin requests, posting to the wall and side-bar activitites
if(isset($_GET['checkin'])) {
	$user->updateUserBrowserActivity(); //set new time session, expand its life.
  	// sleep(1);
  	$userID = $_GET['uid'];
  	$profileName = $_GET['profName'];
  	$profileImage = $_GET['profImg'];

  	$_GET['location_data'] = (!empty($_GET['location_data'])) ? $_GET['location_data'] : ''; //check if value not empty

  	$_GET['location_data'] = htmlentities(stripslashes(strip_tags(trim($_GET['location_data']))));

  	$_GET['location_data'] = str_ireplace("@", '', trim($_GET['location_data'])); //replace @ character if available
  	$placeData = $_GET['location_data'];
  	$location_data = 'checked in - at <span class="fa fa-map-marker"></span>&nbsp;'.$_GET['location_data'];

  	$activity_data = 'checked in - at <span class="animated jello fa fa-map-marker text-info"></span>&nbsp'.$_GET['location_data']; 
 
$recentActivitydata = <<<CHECKINACTIVITY
	<a href='#'><div class='media'><img src='$profileImage' width='30px' class='media-object'><div class='media-body'><span class='media-heading'>$profileName</span><span> $activity_data </span></div></div></a>
CHECKINACTIVITY;
// $data = "<img src='profile/girlcover.jpg' width='20px'/><div>Testing this json data</div>";
//<span class="fa fa-picture-o"></span><br>Link to Google Map View
$curDate = date('M j');
$checkInData = <<<CHECKINDATA
	<h4 class='post-title'><span class="fa fa-map-marker"></span> $profileName check-ins:</h4>
	<a href='#'>
		<div class='checkin-update'>$location_data <br>
		<!-- DIV to link the location google Maps -->
		<div class="wow fadeInUp" data-wow-delay="0.9s" style="text-decoration:none; overflow:hidden; height:250px; width:100%; max-width:100%;">
      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3596.013586101375!2d27.240197650366373!3d-25.670844348655134!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1ebe0960f590b40f%3A0x66c861135890f663!2s57+Boom+St%2C+Rustenburg%2C+2999!5e0!3m2!1sen!2sza!4v1494858295098" style="height:100%;width:100%; border:0;" frameborder="0" allowfullscreen></iframe> <!-- width="300" height="300" -->
    	</div>
    	<br>Google Map <span class="fa fa-street-view"></span> View
    	</div>
    </a>
CHECKINDATA;
$post_card = <<<POSTCARD
	<div class='post-text'>
		<div class='icon-profile'>
			<img src='$profileImage' class='img-responsive img-circle' />
			<span class='username'>$profileName</span>
			<span class='post-time pull-right'>$curDate</span>
		</div>
		<hr>
		<p>$checkInData</p>
	</div>
	<div class='post-footer'>
		<span class='fa fa-heart tag'> <span class='tag-text hidden-xs'>0 Likes</span></span>
		<span class='fa fa-commenting tag'> <span class='tag-text hidden-xs'>0 Comments</span></span>
		<span class='fa fa-retweet tag'> <span class='tag-text hidden-xs'>0 Retweet</span></span>
		<span class='fa fa-share tag'> <span class='tag-text hidden-xs'>0 Shares</span></span>
	</div>
POSTCARD;
if($post->setLocation($userID, $placeData)) {
	if($post->saveWallPost($userID, $checkInData, null)) {
		if($post->updateActivities($userID, $recentActivitydata)) {
			$newdata = array('check_ins' => $recentActivitydata, 'post_card'=> $post_card);
		}
	}
}
echo json_encode($newdata);
exit();
}

// $_POST['publish'] = true;
/*$_POST['publish_data'] = "Hello there [01_y]";*/
//controls the status post publishing from homepage, mixed with emojis icons
if(isset($_POST['publish'])) {
$user->updateUserBrowserActivity(); //set new time session, expand its life.
$publish_data = htmlentities(strip_tags(trim($_POST['publish_data'])));
$userID = (int)trim($_POST['userId']);
$profileImage = trim($_POST['profImage']);
$profileusername = trim(urldecode($_POST['profileName']));
$removedExt = array(); //prepare an empty array to hold data.
$smileys = scandir('emoticons/'); //specify the directory to scan for images.

foreach($smileys as $k=>$v) {
	$removedExt[] = str_replace('.png', '', $v); //create new array to hold values with stripped file extensions.
}
foreach($removedExt as $k=>$v) {
	if($v != '.' && $v != '..') { //skip the directory up level navigations
		$publish_data = str_replace('['.$v.']', '<img src="emoticons/'.$v.'.png" width="50px" />', $publish_data); //now search for matching char within an array if found replace the char with an <img> tag and append its (file.extenstionFormat) and finally display the whole MSG with emoticon(s).
	}
}

function generatePost($params, $profileImage, $profileusername) {
$curDate = date('M j');
$data = <<<HTMLDATA

<div class="post-text">
	<div class="icon-profile">
		<img src="$profileImage" class="img-responsive img-circle" />
		<span class="username">$profileusername</span>
		<span class="post-time pull-right">$curDate</span>
	</div>
	<hr>
	<!-- h4 class="post-title">Test Title</h4 -->
	<p>$params</p>
</div>
<div class="post-footer">
	<span class="fa fa-heart tag"> <span class="tag-text hidden-xs">0 Likes</span></span>
	<span class="fa fa-commenting tag"> <span class="tag-text hidden-xs">0 Comments</span></span>
	<span class="fa fa-retweet tag"> <span class="tag-text hidden-xs">0 Re-post</span></span>
	<span class="fa fa-share tag"> <span class="tag-text hidden-xs">0 Shares</span></span>
</div>
HTMLDATA;
	return $data;
}
	if($publish_data == "") {
		$jasondata['published'] = "Cannot publish empty post!";
		echo json_encode($jasondata);
		exit();
	}
	if($post->saveWallPost($userID, $publish_data, $wall_imagePath=null) == "true") {
		$jasondata = array('published' => generatePost($publish_data, $profileImage, $profileusername) );
	} else {
		$jasondata['published'] = "Failed to save post into database.";
	}
	echo json_encode($jasondata);
	exit();
}


//uploading an image to the wall post
/*$_POST['upload'] = true;
$_POST['contentMessage'] = "Supercool dance...";
$_FILES['mediaUpload']['name'][0] = "profile/breakdance_cover.jpg";*/
if(isset($_POST['wallUpload'])) {
	$user->updateUserBrowserActivity(); //set new time session, expand its life.
	sleep(1);
$userID = (int)$_POST['userId'];
$profileImage = $_POST['profileImage'];
$profileName = $_POST['profileName'];
$messageText = htmlentities(stripslashes(strip_tags(trim($_POST['contentMessage'])))); //get text message
$messageText = nl2br($messageText);
$filename = $_FILES['mediaUpload']['name']; //get media file object
$upfilepath = "wallposts/".$filename; //full upload directory path

function postToWall($fileImage, $postMessage, $profileImage, $profileName) {
	$curDate = date('M j'); //current date
	$responseData = <<<HTMLDATA
<img src="wallposts/$fileImage" class="img-responsive wall-image" onclick="showImage('wallposts/$fileImage');" width="100%;"/>
<div class='post-text'>
	<div class='icon-profile'>
		<img src='$profileImage' class='img-responsive img-circle' />
		<span class='username'>$profileName</span>
		<span class='post-time pull-right'>$curDate</span>
	</div>
	<hr>
	<p>$postMessage</p>
</div>
<div class='post-footer'>
	<span class='fa fa-heart tag'> <span class='tag-text hidden-xs'>0 Likes</span></span>
	<span class='fa fa-commenting tag'> <span class='tag-text hidden-xs'>0 Comments</span></span>
	<span class='fa fa-retweet tag'> <span class='tag-text hidden-xs'>0 Retweet</span></span>
	<span class='fa fa-share tag'> <span class='tag-text hidden-xs'>0 Shares</span></span>
</div>
HTMLDATA;
	return $responseData;
}
function updateActivies() {
	global $profileImage, $profileName;
$activityData = <<<ACTIVITIESDATA
	<a href='#'><div class='media'><img src='$profileImage' width='30px' class='media-object'><div class='media-body'><span class='media-heading'>$profileName</span><span> posted - a <span class="animated jello fa fa-photo text-info"></span> photo on the wall... </span></div></div></a>
ACTIVITIESDATA;
	return $activityData;
}

	if(empty($_FILES['mediaUpload']['name'])) { //if image file posted is empty show this message
		$jasondata = array("wallPostFile" => "<span class='text-danger'>Upload file missing!.</span>");
	}
	if(is_uploaded_file($_FILES['mediaUpload']['tmp_name'])) {
		if($post->saveWallPost($userID, $messageText, $upfilepath)) {
			if($post->updateActivities($userID, updateActivies())) {//update activities right-side-bar panel
		    	$jasondata = array("wallPostFile" => postToWall($filename, $messageText, $profileImage, $profileName), 'recentActivity' => updateActivies());
		    } else {
		    	$jasondata['wallPostFile'] = "Failed to update activities database.";
		    }
	    } else {
	    	$jasondata = array("wallPostFile" => "Failed to save post into database.");
	    }
	}
	if (!move_uploaded_file($_FILES['mediaUpload']['tmp_name'], $upfilepath)) {
		$response = "<span class='text-danger'>Sorry could not upload file to destination directory.</span>";
	    $jasondata = array("wallPostFile" => $response);
	}
	//finally return data
	echo json_encode($jasondata);
	exit();
}

//open an image from the wall to zoomIn to a larger size
if(isset($_GET['openImg'])) {
	if(isset($_SESSION['authorize'])) {$user->updateUserBrowserActivity();} //set new time session, expand its life.
	$imgPath = $_GET['urlImgPath'];
	echo "<div><center><img src='".$imgPath."' class='animated zoomIn' style='width:65%; margin-top:50px'/></center></div>";
	exit();
}
//get all posted contents including images, get from DB
if(isset($_GET['retrievePosts'])) {
$start = (int)$_GET['start'];
$limit = (int)$_GET['limit'];
	if(isset($_SESSION['authorize']) && !empty($_SESSION['authorize'])) { //if session is set now get logged-in user data
		$posts = $post->getWallPosts($start, $limit);
		if($posts != false) :
			foreach ($posts as $post) :
			$userData = $user->getUserDetails($_SESSION['authorize']);
				if( $post->email == $userData->email ) { //if user logged-in show below posts, only current user personal post not all posts.
?>
				<div class="post-item-card">
					<img src="<?php echo $post->image_url; ?>" class="img-responsive wall-image" onclick="showImage('<?php echo $post->image_url; ?>');" width="100%;"/>
					<div class="post-text">
						<div class="icon-profile">
							<img src="<?php echo $userData->imageUrl; ?>" class="img-responsive img-circle" />
							<span class="username"><?php echo $userData->username; ?></span>
							<span class="post-time pull-right" ><?php echo $hash->timeDiff($post->created); ?></span>
							<!-- <span class="post-time pull-right" ><?php #echo date('M j', strtotime($post->created)); ?></span> -->
						</div>
						<hr>

						<p><?php echo $post->post_content; ?></p>
					</div>
					<div class="post-footer">
						<span class="fa fa-heart tag"> <span class="tag-text hidden-xs">0 Likes</span></span>
						<span class="fa fa-commenting tag"> <span class="tag-text hidden-xs">0 Comments</span></span>
						<span class="fa fa-retweet tag"> <span class="tag-text hidden-xs">0 Retweet</span></span>
						<span class="fa fa-share tag"> <span class="tag-text hidden-xs">0 Shares</span></span>
					</div>
				</div>
<?php
			} //END confirm userdetails
			endforeach; //END foreach
		endif; //END countPosts > 0
	} //end $_SESSION

	else { // Else if not logged-in then show all posts
		$posts = $post->getWallPosts($start, $limit);
		if($posts != false) {
			foreach ($posts as $post) {
?>
			<div class="post-item-card">
				<img src="<?php echo $post->image_url; ?>" class="img-responsive wall-image" onclick="showImage('<?php echo $post->image_url; ?>');" width="100%;"/>
				<div class="post-text">
					<div class="icon-profile">
						<img src="<?php echo $post->imageUrl; ?>" class="img-responsive img-circle" />
						<span class="username"><?php echo $post->username; ?></span>
						<span class="post-time pull-right" ><?php echo $hash->timeDiff($post->created); ?></span>
						<!-- <span class="post-time pull-right" ><?php #echo date('M j', strtotime($post->created)); ?></span> -->
					</div>
					<hr>

					<p><?php echo $post->post_content; ?></p>
				</div>
				<div class="post-footer">
					<span class="fa fa-heart tag"> <span class="tag-text hidden-xs">0 Likes</span></span>
					<span class="fa fa-commenting tag"> <span class="tag-text hidden-xs">0 Comments</span></span>
					<span class="fa fa-retweet tag"> <span class="tag-text hidden-xs">0 Retweet</span></span>
					<span class="fa fa-share tag"> <span class="tag-text hidden-xs">0 Shares</span></span>
				</div>
			</div>
<?php
			}
		} else { return false; }
	} //END else
	exit();
} //END if GLOBAL $_GET=>allPosts

//get all recent activities from DB
if(isset($_GET['retieveActivities'])) {
	$rows = $post->getAllActivies();
	if(!empty($rows)) {
		foreach ($rows as $row) {
			echo '<div class="activity">';
			echo 	$row->activity_post;
			echo '</div>';
		}
	}
	else {
		echo "<center><strong><span class='fa fa-info-circle fa-1x'></span> No recent activities.</strong></center>";
	}
}

if (isset($_POST['inboxSend'])) { //handles to send message
	$user->updateUserBrowserActivity(); //set new time session, expand its life.
	sleep(2);
	$sender = htmlentities(strip_tags(trim($_POST['senderEmail'])));
	$names = htmlentities(strip_tags(trim($_POST['names'])));
	$recipient = htmlentities(strip_tags(trim($_POST['recipientEmail'])));
	$messageBody = htmlentities(strip_tags(trim($_POST['message'])));
	$messageBody = nl2br($messageBody);
	$results = $msg->sendMessage($sender, $names, $recipient, $messageBody);
	if($results == "true") {
		$response['responseData'] = "success";
	} else {
		$response['responseData'] = $results;
		// $response['responseData'] = "failed";
	}
	echo json_encode($response);
	exit();
}

if (isset($_POST['retrieveInbox'])) { //handles to retrieve inbox messages and list them on navigation drop-down
	$recipient_email = htmlentities(strip_tags(trim($_POST['email'])));
	$allunread = $msg->getunreadMessages($recipient_email); //get all unread messages
	$img = $user->getAllUsers(); //get sender's profile image

	if(!empty($allunread)) {
		?>
		<li  class="message-preview"><a href="javascript:writeMsg()" class="createMsg"><span class="fa fa-edit"></span> Compose message</a></li>
		<li class="message-preview">
          	<a href="javascript:getMessageBox('unreadMsg')"><span class="glyphicon glyphicon-flag text-success"></span> 
          	<i><?php (count($allunread) > 1) ? print count($allunread)." New messages" : print count($allunread)." New message"; ?> </i></a>
      	</li>
		<?php
		foreach($allunread as $msgbox) {
			foreach($img as $row) {
				if($row->email == $msgbox->sender_email) {
					$profileImage = $row->imageUrl;
				}
				// break;
			}
?>
		<li class="message-preview" style="background-color: #f5f5f5;">
			<a href="javascript:getMessageBox('unreadMsg')">
				<div class="media">
					<span class="pull-left">
						<img src="<?php echo $profileImage; ?>" class="media-object img-responsive img-circle"/>
					</span> <!-- profile/avatars/avatar_192px.png -->
					<div class="media-body">
						<div class="media-heading"><strong><?php echo $msgbox->names; ?></strong></div>
						<p class="small text-muted"><i class="fa fa-clock-o"></i> <?php echo $msg->formatDate($msgbox->created); ?></p>
						<!-- <p class="small text-muted"><i class="fa fa-clock-o"></i> <?php echo date('D d, M \a\t H:i A', strtotime($msgbox->created)); ?></p> -->
						<div class="message-body"><?php echo $msgbox->message; ?></div>
					</div>
				</div>
			</a>
		</li>
<?php
		} //foreach END
		echo '<li class="message-preview"><a href="javascript:getMessageBox(\'allMsg\')"><span class="fa fa-envelope-open-o"></span> All read messages</a></li>';
	}
	else {
?>
		<li class="message-preview">
			<a href="javascript:void(0)"><span class="fa fa-info-circle text-muted"></span> <i class="text-muted">No new messages</i></a>
		</li>
		<li class="message-preview"><a href="javascript:writeMsg()" class="createMsg"><span class="fa fa-edit"></span> Compose message</a></li>
		<li class="message-preview"><a href="javascript:getMessageBox('readMsg')"><span class="fa fa-envelope-open-o"></span> All read messages</a></li>
<?php
	}
	exit();
}

//search for posted content
if(isset($_GET['search']) && $_GET['search'] == true) {
	if(!empty($_GET['q'])) {
		$user->updateUserBrowserActivity(); //set new time session, expand its life.
		$term = strtolower($_GET['q']);
		$strippedTerm = htmlentities(stripslashes(strip_tags(trim($term))));
		// $emphasizeTerm = "<strong>".$searchTerm."</strong>";
		$searchTerm = str_replace(" ", "%", $strippedTerm);
		$results = $post->search($searchTerm);
		if(!empty($results)) {
			foreach ($results as $rows) :
				$posted_content = strtolower(stripslashes($rows->post_content)); //clean content from database & convert to lowecase
				$emphasized = "<strong class='text-primary'>{$term}</strong>"; //bold the user search term
				$altered_content = str_replace($searchTerm, $emphasized, $posted_content); //then finally incorporate everything
?>
				<div class="post-item-card">
					<img src="<?php echo $rows->image_url; ?>" class="img-responsive wall-image" onclick="showImage('<?php echo $rows->image_url; ?>');" width="100%;"/>
					<div class="post-text">
						<div class="icon-profile">
							<img src="<?php echo $rows->imageUrl; ?>" class="img-responsive img-circle" />
							<span class="username"><?php echo $rows->username; ?></span>
							<span class="post-time pull-right" ><?php echo $hash->timeDiff($rows->created); ?></span>
							<!-- <span class="post-time pull-right" ><?php #echo date('M j', strtotime($rows->created)); ?></span> -->
						</div>
						<hr>
						<p><?php echo $altered_content; ?></p>
					</div>
				</div>
<?php
			endforeach;
		}
		else {
			echo "<div class='alert alert-info'><center><strong><span class='fa fa-info-circle'></span> No match found!</strong></center></div>";
		}
	}
	else {
		echo "<div class='alert alert-danger'><center><strong><span class='fa fa-warning'></span> Please type something to search!</strong></center></div>";
	}
	exit();
}

//filter friends list, when typing names to search
if(isset($_GET['queryusers'])) {
	// sleep(1);
	$queryName = htmlentities(stripslashes(strip_tags(trim($_GET['queryusers']))));
	if(!empty($queryName)) {
		$inviteSenderEmail = (isset($_SESSION['authorize'])) ? $_SESSION['authorize'] : null;
		$queryName = strtolower($queryName);
		$searchName = str_replace(" ", "%", $queryName);
		$allusers = $user->filterUsers($searchName);
		if($allusers) {
			$friendsList = $notifyObj->getAllFriends($inviteSenderEmail); //get all friends in your list
			$invited = 0;
			foreach($allusers as $row) :
				if($friendsList==true) { //if result set of invited friends list not emptyx execute below, else skip
					foreach($friendsList as $friend) {
						if($row->email == $friend->invite_by) {
							$invited = 1; //if user already invited
							break;
						}
					}
				}
				if($row->email == $inviteSenderEmail) { //if you try to search yourself skip your profile
					$namesFound = ""; //set result set to empty string
					$filteredata = null; //set the results to null/empty if names matches user profile
					continue;
				}
				$uname = strtolower(stripslashes($row->username)); //clean content from database & convert to lowecase
				$emphasized = "<strong class='text-danger'>".$queryName."</strong>"; //bold the user search term
				$namesFound = str_replace($searchName, $emphasized, $uname); //then finally incorporate everything
				/*?>*/
$filteredata = <<<HTMLDATA
				<li class="result-item" 
					data-uid="$row->id" 
					data-email="$row->email" 
					data-names="$uname" 
					data-invited="$invited"
					data-accountstatus="$row->status"
					data-url="$row->imageUrl" onclick="getUser(this, '$inviteSenderEmail');" >
					<img src="$row->imageUrl" />$namesFound
				</li>
HTMLDATA;
	/*<?php*/
			endforeach;
			$jasondata['results'] = $filteredata;
			// echo $filteredata;
		}
		else {
			$jasondata['results'] = "<li class='result-item-none'>No Match Found!.</li>";
			// echo "<li class='result-item-none'>No Match Found!.</li>";
		}
	}
	else {
		$jasondata['results'] = "<li class='result-item-none'>No Match Found!.</li>";
		// echo "<li class='result-item-none'>No Match Found!.</li>";
	}
	echo json_encode($jasondata);
	exit();
}
//sending an invitation to friends
if(isset($_POST['invite'])) {
	$user->updateUserBrowserActivity(); //set new time session, expand its life.
	$user_id = htmlentities(stripslashes(strip_tags(trim($_POST['uid']))));
	$inviteTo = htmlentities(stripslashes(strip_tags(trim($_POST['inviteTo']))));
	$inviteFrom =  htmlentities(stripslashes(strip_tags(trim($_POST['inviteFrom']))));
	$user_id = (int)$user_id;
	if(empty($user_id) || empty($inviteTo)) {
		$response['message'] = "failed";
	}
	if(!filter_var($inviteTo, FILTER_VALIDATE_EMAIL) && !filter_var($inviteFrom, FILTER_VALIDATE_EMAIL)) {
		$response['message'] = "failed";
	}
	if($notifyObj->sendInvitation($user_id, $inviteTo, $inviteFrom)) { //if returns true show message
		$response['message'] = "Invitation sent successfully!";
	}
	else {
		$response['message'] = "failed"; //if failed to save invitation to DB
	}
	echo json_encode($response);
	exit();
}
//get all invitaions and display on Menu drop-down notifications
if(isset($_GET['getinvites'])) {
	$email = (isset($_SESSION['authorize'])) ? $_SESSION['authorize'] : null;
	$img = $user->getAllUsers();
	
	$email = htmlentities(stripslashes(strip_tags(trim($email)))); //$_GET['email']
	if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$invites = $notifyObj->getInvitations($email);
		if(!empty($invites)) {
		?>
			<li><a href="javascript:inviteFilter()"><span class="fa fa-users"></span> Manage Friends</a></li>
			<li class="divider"></li>
			<li>
				<a href="javascript:showInviteList()"><span class="glyphicon glyphicon-flag text-success"></span> 
				<?php (count($invites) > 0) ? print count($invites).' pending invitations' : print count($invites).' pending invitation'; ?></a>
			</li>
			<li class="divider"></li>
		<?php
		foreach($invites as $invite) :
			foreach($img as $row) {
				if($row->email == $invite->invite_by) {
					$profileImage = $row->imageUrl;
					$names = $row->username;
					$profEmail = $row->email;
					break;
				}
			}
?>
			<li><a data-names="<?php echo $names; ?>" data-stamp="<?php echo $invite->created; ?>" data-email="<?php echo $profEmail; ?>" data-url="<?php echo $profileImage; ?>" data-owner="<?php echo $email; ?>" onclick="openInvite(this)" href="javascript:void(0)">
				<img src="<?php echo $profileImage ?>" width="25px"/> <div class="label label-success"><?php echo $names; ?></div></a>
			</li>
<?php
		endforeach;
		} else {
			// echo '<li><a href="javascript:void(0)"><span class="fa fa-info-circle"></span> No invitation</a></li>';
?>
				<li><a href="javascript:void(0)"><span class="fa fa-info-circle text-muted"></span> <i class="text-muted">No new invitations</i></a></li>
				<li class="divider"></li>
				<li><a href="javascript:inviteFilter()"><span class="fa fa-users"></span> Manage Friends</a></li>
<?php
		}
	} /*filter Email END*/
	exit();
}
//retrieve a list of all invitation friends requests
if(isset($_GET['invitationlist'])) {
	$email = htmlentities(stripslashes(strip_tags(trim($_GET['email']))));
	$userImg = $user->getAllUsers();
	$invites = $notifyObj->getInvitations($email);
	if($invites==true) {
	?>
		<li class="invite-item-head text-center">
			<span class="glyphicon glyphicon-flag text-success"></span> 
			<?php (count($invites) > 0) ? print count($invites).' pending invitations' : print count($invites).' pending invitation'; ?>
		</li>
	<?php
		foreach($invites as $invite) :
			foreach($userImg as $row) {
				if($row->email == $invite->invite_by) {
					$profileImage = $row->imageUrl;
					$names = $row->username;
					$profEmail = $row->email;
					break;
				}
			}
?>
		<li class="invite-item" title="View Invite" data-names="<?php echo $names; ?>" data-stamp="<?php echo $invite->created; ?>" data-email="<?php echo $profEmail; ?>" data-url="<?php echo $profileImage; ?>" data-owner="<?php echo $email; ?>" onclick="openInvite(this)">
			<img src="<?php echo $profileImage ?>" width="25px"/><?php echo $names; ?>
		</li>
<?php
		endforeach;
	} else {
?>
		<li class="invite-item-head text-center"><span class="fa fa-info-circle"></span> <i>No new invitations</i></li>
<?php
	}
}
//update invitation if invited receipient clicked on Accept
if(isset($_POST['accepted'])) {
	$user->updateUserBrowserActivity(); //set new time session, expand its life.
	sleep(2);
	$inviteDate = htmlentities(stripslashes(strip_tags(trim($_POST['timeStamp']))));
	$userEmail = htmlentities(stripslashes(strip_tags(trim($_POST['user']))));
	if($notifyObj->acceptInvite($userEmail, $inviteDate)) {
		$response['message'] = "<span class='animated flash'>You have accepted invitation, you are now friends!</span>";
	} else {
		$response['message'] = "<span class='animated flash'>Sorry, failed to process friends request.</span>";
	}
	echo json_encode($response);
	exit();
}
//this routes the request to fetch all friends list data
if(isset($_GET['allfriends'])) {
	$user->updateUserBrowserActivity(); //set new time session, expand its life.
	$authorized = (isset($_SESSION['authorize'])) ? $_SESSION['authorize'] : null; //for current user who is authorized
	$friendsList = $notifyObj->getFriendsList($authorized); //get all friends in your list
	$invites = $notifyObj->getInvitations($authorized); //get all pending invitations
	if(!empty($friendsList)) {
		?>
		 <li class="friends-output-header">
			 <center>You have <?php (count($friendsList) >= 2) ? print count($friendsList).' friends' : print count($friendsList).' friend'; ?></center>
		 </li>
		 <?php
		 	($invites==true) ? print "<li class='friends-output-header animated flash' title='View invitations' onclick='showInviteList()'><center>You have <small class='pendingInvites'>".count($invites)."</small> Pending Invitations</center></li>" : print '';
		 ?>
		<?php
		$allUsers = $user->getAllUsers(); //now get all user to retrieve names and profileImages
		foreach($friendsList as $friend) :
			foreach($allUsers as $row) {
				if($friend->invite_by == $row->email) {
					$profileImage = $row->imageUrl;
					$names = $row->username;
					$uid = $row->id;
					$friendsEmail = $row->email;
					$friendAccStatus = $row->status;
					break;
				}
			}
	?>
		<li class="friends-output" onclick="getProfile(this)" title="View Profile" data-invited="1" data-uid="<?php echo $uid; ?>" data-imageurl="<?php echo $profileImage; ?>" data-username="<?php echo $names; ?>" data-email="<?php echo $friendsEmail; ?>" data-accountstatus="<?php echo $friendAccStatus; ?>" data-senderemail="<?php echo $authorized; ?>" >
			<img src="<?php echo $profileImage; ?>"/> <?php echo $names; ?>
		</li>
		<!-- <li class="friends-output" onclick="getEvent('message','<?php echo $friendsEmail; ?>')"><img src="<?php echo $profileImage; ?>"/> <?php echo $names; ?><span class="fa fa-envelope pull-right"> Message</span></li> -->
	<?php
		endforeach;
	}
	else {
	?>
		<li class="friends-output-header"><center>You have no friends in your list yet.</center></li>
	<?php
	}
}

if(isset($_GET['countAlerts'])) {
	$userEmail = (isset($_SESSION['authorize'])) ? $_SESSION['authorize'] : null;
	$invites = $notifyObj->getInvitations($userEmail); //update invitation numer
	$friendsList = $notifyObj->getFriendsList($userEmail); //update friendsList number
	$checkins = $post->getPlaces(); //update list of places visited

	$inviteCount = (!empty($invites)) ? count($invites) : 0;
	$countFriends = (!empty($friendsList)) ? count($friendsList) : 0;
	$countCheckins = (!empty($checkins)) ? count($checkins) : 0;

	$jasondata['checkinsCount'] = $countCheckins;
	$jasondata['invitesCount'] = $inviteCount;
	$jasondata['friendsCount'] = $countFriends;
	echo json_encode($jasondata);
	exit();
}

if(isset($_POST['changepass'])) {
	$user->updateUserBrowserActivity(); //set new time session, expand its life.
	$email = (isset($_SESSION['authorize'])) ? $_SESSION['authorize'] : '';
	$oldpassword = htmlentities(stripslashes(strip_tags(trim($_POST['oldpassword']))));
	$newpassword = htmlentities(stripslashes(strip_tags(trim($_POST['newpassword']))));
	// $email = trim($_POST['email']);
	if($user->updatePassword($oldpassword, $newpassword, $email)) { //
		$jasondata['message'] = "<span class='animated rubberBand'>Password Successfully changed.</span>";
	}
	else {
		$jasondata['message'] = "<span class='animated shake'>Sorry, we failed to update your password.</span>";
	}
	// ob_flush();
	echo json_encode($jasondata);
	exit();
}

if(isset($_POST['updateprofile'])) {
	$user->updateUserBrowserActivity(); //set new time session, expand its life.
	$uid = htmlentities(strip_tags(trim($_POST['uid'])));
	$firstname = htmlentities(strip_tags(trim($_POST['firstname'])));
	$lastname = htmlentities(strip_tags(trim($_POST['lastname'])));
	$email = htmlentities(strip_tags(trim($_POST['email'])));

	if( !empty($firstname) && !empty($lastname) && !empty($email) ) {
		// $response['message'] = "ID:".$uid."Firstname:".$firstname." Lastname:".$lastname." Email:".$email;
		if( $user->updateAccount($uid, $firstname, $lastname, $email) ) {
			$response['message'] = "success";
		}
		else {
			$response['message'] = "failed";
		}
	}
	else {
		$response['message'] = "failed";
	}
	echo json_encode($response);
	exit();
}

//check again and run cronJob after 10 hours 
if(isset($_GET['runcronjob'])) { # 900sec -> 15min
	if( $cron->runCron() ) { //run the cronJob where it will set a new time from there
		$response['response'] = "true"; //if is yes is older then log user out.
		session_unset($_SESSION['crontime']); //reset the time
	}
	else {
		$response['response'] = "false"; //else do nothing
	}
	echo json_encode($response);
	exit();
}
?>

