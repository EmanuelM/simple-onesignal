<?php
	namespace OneSignal;
	require('src/OneSignal.php');
	require('src/notification.php');

	use OneSignal\OneSignal;
	use OneSignal\Notification;

	/* Init app */
	$onesignal = new OneSignal("APP_ID", "APP_KEY");
	/* New push */
	$push = $onesignal->notification();
	/* Add user */
	$push->addUser($_POST['onesignal_id']);
	/* Set title and message */
	$push->setTitle($_POST['push_title']);
	$push->setMessage($_POST['push_content']);
	/* Send */
	// var_dump($push); exit;
	$response = $onesignal->send($push);
	echo json_encode($response);
?>