<?php
	require('classes/OneSignal.php');

	/* Init app */
	$onesignal = new OneSignal("APP_ID", "API_KEY", "LANGUAGE");
	/* Add user */
	$onesignal->addUser($_POST['onesignal_id']);
	/* Set title and message */
	$onesignal->setTitle($_POST['push_title']);
	$onesignal->setMessage($_POST['push_content']);
	/* Send */
	$response = $onesignal->send();
	echo json_encode($response);
?>