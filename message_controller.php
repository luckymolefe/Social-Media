<?php

if(in_array('user_model.php', scandir('model/'))) {
	require_once('model/user_model.php');
	require_once('model/posts_model.php');
	require_once('model/inbox_model.php');
}

//get all messages Read and Unread
if (isset($_POST['allMsg'])) {
	$user->updateUserBrowserActivity(); //set new time session, expand its life.
	$userEmail = (isset($_SESSION['authorize'])) ? $_SESSION['authorize'] : null;
	$allMessages = $msg->getAllMessages($userEmail);

	if(!empty($allMessages)) {
		$img = $user->getAllUsers(); //get sender's profile image
		foreach ($allMessages as $message) :
			foreach($img as $row) {
				if($row->email == $message->sender_email) {
					$profileImage = $row->imageUrl;
				}
			}
?>
			
				<?php if($message->message_status == "1") { ?>
				<div class="message-item" onclick="viewMessage('<?php echo $message->created; ?>','allMsg')">
					<span class="delete-item" title="remove message" onclick="archiveItem('<?php echo $message->created; ?>','allMsg');">&times;</span>
					<span class="read-item" title="flagged as read"><i class="fa fa-envelope-open-o"></i></span>
				<?php } else { ?>
				<div class="message-item-unread" onclick="viewMessage('<?php echo $message->created; ?>','allMsg')">
					<span class="delete-item" title="remove message" onclick="archiveItem('<?php echo $message->created; ?>','allMsg');">&times;</span>
				<?php } ?>
				<img src="<?php echo $profileImage; ?>" class="message-photo"/>
				<div class="senderNames">
					<?php echo $message->names; ?>
					<span style="float: right;font-weight: lighter;" class="received_date"><?php echo $msg->formatDate($message->created); ?></span>
					<!-- <span style="float: right;font-weight: lighter;" class="received_date"><?php echo date('D, d M \a\t H:i A', strtotime($message->created)); ?></span> -->
				</div>
				<div class="senderEmail">
					<?php echo $message->sender_email; ?>
				</div>
				<div class="messageBody"><?php echo $message->message; ?></div>
			</div>
<?php
		endforeach;
	}
	else {
?>
			<div class="message-item">
				<center><strong>No Messages available.</strong></center>
			</div>
<?php
	}

}
//get all unread messages
if(isset($_POST['unreadMsg'])) {
	$user->updateUserBrowserActivity(); //set new time session, expand its life.
	$userEmail = (isset($_SESSION['authorize'])) ? $_SESSION['authorize'] : null;
	$allUnread = $msg->getunreadMessages($userEmail);
	if(!empty($allUnread)) {
		$img = $user->getAllUsers(); //get sender's profile image
		foreach($allUnread as $unreadMsg) :
			foreach($img as $row) {
				if($row->email == $unreadMsg->sender_email) {
					$profileImage = $row->imageUrl;
				}
			}
?>
			<div class="message-item-unread" onclick="viewMessage('<?php echo $unreadMsg->created; ?>', 'unreadMsg');">
				<i class="delete-item" title="remove message" onclick="archiveItem('<?php echo $unreadMsg->created; ?>','unreadMsg');">&times;</i>
				<img src="<?php echo $profileImage; ?>" class="message-photo"/>
				<div class="senderNames">
					<?php echo $unreadMsg->names; ?>
					<span style="float: right;font-weight: lighter;" class="received_date"><?php echo  $msg->formatDate($unreadMsg->created); ?></span>
					<!-- <span style="float: right;font-weight: lighter;" class="received_date"><?php echo date('D, d M \a\t H:i A', strtotime($unreadMsg->created)); ?></span> -->
				</div>
				<div class="senderEmail">
					<?php echo $unreadMsg->sender_email; ?>
				</div>
				<div class="messageBody"><?php echo $unreadMsg->message; ?></div>
				<!-- <span class="read-item" title="open message"><i class="fa fa-envelope"></i></span> -->
			</div>
<?php
		endforeach;
	}
	else {
?>
			<div class="message-item">
				<center><strong>No New Messages.</strong></center>
			</div>
<?php
	}
}

//get all read messages
if(isset($_POST['readMsg'])) {
	$user->updateUserBrowserActivity(); //set new time session, expand its life.
	$userEmail = (isset($_SESSION['authorize'])) ? $_SESSION['authorize'] : null;
	$allread = $msg->getreadMessages($userEmail);
	if(!empty($allread)) {
		$img = $user->getAllUsers(); //get sender's profile image
		foreach($allread as $readMsg) :
			foreach($img as $row) {
				if($row->email == $readMsg->sender_email) {
					$profileImage = $row->imageUrl;
				}
			}
?>
			<div class="message-item" onclick="viewMessage('<?php echo $readMsg->created; ?>','readMsg')">
			<span class="delete-item" title="remove message" onclick="archiveItem('<?php echo $readMsg->created; ?>','readMsg')">&times;</span>
			<span class="read-item" title="flagged as read"><i class="fa fa-envelope-open-o"></i></span>
				<img src="<?php echo $profileImage; ?>" class="message-photo"/>
				<div class="senderNames">
					<?php echo $readMsg->names; ?>
					<span style="float: right;font-weight: lighter;" class="received_date"><?php echo $msg->formatDate($readMsg->created); ?></span>
					<!-- <span style="float: right;font-weight: lighter;" class="received_date"><?php echo date('D, d M \a\t H:i A', strtotime($readMsg->created)); ?></span> -->
				</div>
				<div class="senderEmail">
					<?php echo $readMsg->sender_email; ?>
				</div>
				<div class="messageBody"><?php echo $readMsg->message; ?></div>
			</div>
<?php
		endforeach;
	} else {
?>
			<div class="message-item">
				<center><strong>No Messages Available.</strong></center>
			</div>
<?php
	}
}
//retrieve archive messages
if(isset($_POST['archives'])) {
	$user->updateUserBrowserActivity(); //set new time session, expand its life.
	$userEmail = (isset($_SESSION['authorize'])) ? $_SESSION['authorize'] : null;
	$allArchived = $msg->getArchivedMessages($userEmail);
	if(!empty($allArchived)) {
		$img = $user->getAllUsers(); //get sender's profile image
		foreach($allArchived as $archMsg) :
			foreach($img as $row) {
				if($row->email == $archMsg->sender_email) {
					$profileImage = $row->imageUrl;
				}
			}
?>
			<div class="message-item" onclick="viewMessage('<?php echo $archMsg->created; ?>','archives')">
				<span class="delete-item" title="restore message" onclick="restoreItem('<?php echo $archMsg->created; ?>')"><i class="fa fa-mail-reply"></i></span>
				<span class="read-item" title="flagged as deleted"><i class="fa fa-trash"></i></span>
				<img src="<?php echo $profileImage; ?>" class="message-photo"/>
				<div class="senderNames">
					<?php echo $archMsg->names; ?>
					<span style="float: right;font-weight: lighter;" class="received_date"><?php echo $msg->formatDate($archMsg->created); ?></span>
					<!-- <span style="float: right;font-weight: lighter;" class="received_date"><?php echo date('D, d M \a\t H:i A', strtotime($archMsg->created)); ?></span> -->
				</div>
				<div class="senderEmail">
					<?php echo $archMsg->sender_email; ?>
				</div>
				<div class="messageBody"><?php echo $archMsg->message; ?></div>
			</div>
<?php
		endforeach;
	} else {
?>
			<div class="message-item">
				<center><strong>No Archived Messages.</strong></center>
			</div>
<?php
	}
}

//open to read message
if(isset($_POST['markRead'])) {
	$user->updateUserBrowserActivity(); //set new time session, expand its life.
	$messageDated = $_POST['message_date'];
	if($msg->markMessageRead($messageDated)) {
		echo "true";
	}
	else {
		echo "false";
	}
	exit();
}

if(isset($_POST['archiveMsg'])) {  //set message flag as archive
	$messageDated = $_POST['message_date'];
	if($msg->archiveMessage($messageDated)) {
		echo "Message archived!";
	}
	else {
		echo "Failed to archive message!";
	}
	exit();
}

if(isset($_POST['restoreMsg'])) {  //restore message from archive
	$messageDated = $_POST['message_date'];
	if($msg->restoreMessage($messageDated)) {
		echo "Message restored!";
	}
	else {
		echo "Failed to restore message!";
	}
	exit();
}

if(isset($_GET['countInbox'])) {
	$userEmail = (isset($_SESSION['authorize'])) ? $_SESSION['authorize'] : null;
	$inbox = $msg->getunreadMessages($userEmail);
	$inboxCount = (!empty($inbox)) ? count($inbox) : 0;
	$jasondata['response'] = $inboxCount;
	echo json_encode($jasondata);
	exit();
}

?>