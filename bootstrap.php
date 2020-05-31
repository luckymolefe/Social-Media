<?php

	//boot strapping
	if(in_array('user_model.php', scandir('model/'))) {
		require_once('model/user_model.php');
		require_once('model/posts_model.php');
		require_once('model/notifications_model.php');
		require_once('model/inbox_model.php');
		require_once('model/cronjob_model.php');
	}

?>