<?php
	require('classes/OneSignal.php');

	/* Init app */
	$onesignal = new OneSignal("APP_ID", "API_KEY", "LANGUAGE");
	/* New push */
	$push = $onesignal->new();
	/* Add user */
	$push->addUser($_POST['onesignal_id']);
	/* Set title and message */
	$push->setTitle($_POST['push_title']);
	$push->setMessage($_POST['push_content']);
	/* Send */
	$response = $onesignal->send($push);
	echo json_encode($response);
?>