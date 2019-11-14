<?php
	require('OneSignal_Push.php');

	class OneSignal {
		private $app_id;
		private $app_key;
		private $app_language;

		/* init OneSignal */
		public function __construct($app_id, $app_key, $app_language) {
			$this->app_id       = $app_id;
			$this->app_key      = $app_key;
			$this->app_language = $app_language;
		}

		/* new OneSignal Push */
		public function new() {
			return new OneSignal_Push;
		}

		/* make push array */
		public function makePush($push) {
			$message = [];
			// check app id
			if (empty($this->app_id)) {
				return [
					"success" => false,
					"error"   => "No app_id",
				];
			}
			// check public
			if (empty($push->push_public) && empty($push->push_segment)) {
				return [
					"success" => false,
					"error"   => "No public/segment",
				];
			}
			// check title
			if (empty($push->push_title)) {
				return [
					"success" => false,
					"error"   => "Title required",
				];
			}
			// check message content
			if (empty($push->push_message)) {
				return [
					"success" => false,
					"error"   => "You need to set a message",
				];
			}
			/* general push */
			$message = [
				"app_id" => $this->app_id,
				"headings" => [$this->app_language => $push->push_title],
				"contents" => [$this->app_language => $push->push_message],
			];
			/* add extras */
			// -> subtitle
			if (!empty($push->push_subtitle)) $message['subtitle'] = [$this->app_language => $push->push_subtitle];
			// -> url
			if (!empty($push->push_url)) $message['url'] = $push->push_url;
			// -> buttons
			if (!empty($push->push_buttons)) $message['buttons'] = $push->push_buttons;
			// -> segment
			if (!empty($push->push_segment)) $message['included_segments'] = $push->push_segment;
			// -> users
			if (!empty($push->push_public)) $message['include_player_ids'] = $push->push_public;

			return $message;
		}

		/* send push notification */
		public function send($push) {
			$push = $this->makePush($push);
			// error making push
			if (isset($push['success']) && !$push['success']) return $push;
			// all ok, continue
			$push = json_encode($push);
			// cURL
	        $client = curl_init();
	        curl_setopt($client, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
	        curl_setopt($client, CURLOPT_HTTPHEADER, ["Content-Type: application/json; charset=utf-8", "Authorization: Basic $this->app_key"]);
	        curl_setopt($client, CURLOPT_RETURNTRANSFER, TRUE);
	        curl_setopt($client, CURLOPT_HEADER, FALSE);
	        curl_setopt($client, CURLOPT_POST, TRUE);
	        curl_setopt($client, CURLOPT_POSTFIELDS, $push);
	        curl_setopt($client, CURLOPT_SSL_VERIFYPEER, FALSE);

	        $response = curl_exec($client);
	        $response = json_decode($response);
	        curl_close($client);
	        // response
	        return $response;
		}
	}
?>
