<?php
	namespace OneSignal;
	use OneSignal\Notification;

	class OneSignal
	{
		private $app_id;
		private $app_key;
		private $app_language;

		/**
		 * Init OneSignal
		 * @param string $app_id
		 * @param string $app_key
		 * @param string $app_language
		 */
		public function __construct(string $app_id = "", string $app_key = "", string $app_language = "en")
		{
			$this->app_id       = $app_id;
			$this->app_key      = $app_key;
			$this->app_language = $app_language;
		}

		/**
		 * New push Notification
		 * @return Notification
		 */
		public function notification()
		{
			return new Notification;
		}

		/**
		 * Make push array
		 * @param  Notification $push
		 * @return array
		 */
		private function makeNotification(Notification $push = null)
		{
			$message = [];
			// check app id
			if (empty($this->app_id))
			{
				return [
					"success" => false,
					"error"   => "No app_id",
				];
			}
			// check public
			if (empty($push->public) && empty($push->segment))
			{
				return [
					"success" => false,
					"error"   => "No public/segment",
				];
			}
			// check title
			if (empty($push->title))
			{
				return [
					"success" => false,
					"error"   => "Title required",
				];
			}
			// check message content
			if (empty($push->message))
			{
				return [
					"success" => false,
					"error"   => "You need to set a message",
				];
			}
			/* general push */
			$message = [
				"app_id"   => $this->app_id,
				"headings" => [$this->app_language => $push->title],
				"contents" => [$this->app_language => $push->message],
			];
			/* add extras */
			// -> subtitle
			if (!empty($push->subtitle)) $message['subtitle'] = [$this->app_language => $push->subtitle];
			// -> url
			if (!empty($push->url)) $message['url'] = $push->url;
			// -> buttons
			if (!empty($push->buttons)) $message['buttons'] = $push->buttons;
			// -> segment
			if (!empty($push->segment)) $message['included_segments'] = $push->segment;
			// -> users
			if (!empty($push->public)) $message['include_player_ids'] = $push->public;

			return $message;
		}

		/**
		 * Send push notification
		 * @param  Notification $push
		 * @return array
		 */
		public function send(Notification $push = null)
		{
			$push = $this->makeNotification($push);
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
