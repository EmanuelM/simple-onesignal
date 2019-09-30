<?php
	class OneSignal {
		private $app_id;
		private $app_key;
		private $app_language;
		// push parameters
		private $push_public;
		private $push_title;
		private $push_subtitle;
		private $push_message;
		private $push_url;
		private $push_buttons;
		private $push_segment;

		/* init OneSignal */
		public function __construct($app_id, $app_key, $app_language) {
			$this->app_id       = $app_id;
			$this->app_key      = $app_key;
			$this->app_language = $app_language;
			// init arrays
			$this->push_public  = [];
			$this->push_buttons = [];
			$this->push_segment = [];
		}

		/**
		 * Add a user to send
		 * @param String $player_id - OneSignal player id
		 */
		public function addUser($player_id) {
			array_push($this->push_public, $player_id);
		}

		/**
		 * Set push title
		 * @param String $title
		 */
		public function setTitle($title) {
			$this->push_title = $title;
		}

		/**
		 * Set push subtitle. Only web browsers
		 * @param String $subtitle
		 */
		public function setSubtitle($subtitle) {
			$this->push_subtitle = $subtitle;
		}

		/**
		 * Set push message
		 * @param String $message
		 */
		public function setMessage($message) {
			$this->push_message = $message;
		}

		/**
		 * Set callback url
		 * @param String $url
		 */
		public function setUrl($url) {
			$this->push_url = $url;
		}

		/**
		 * Add button
		 * @param String $id
		 * @param String $text
		 */
		public function addButton($id, $text) {
			array_push($this->push_buttons, ["id" => $id, "text" => $text]);
		}

		/**
		 * Add a segment to send
		 * @param String $segment
		 */
		public function addSegment($segment) {
			array_push($this->push_segment, $segment);
		}

		/* make push array */
		public function makePush() {
			$push = [];
			// check app id
			if (empty($this->app_id)) {
				return [
					"success" => false,
					"error"   => "No app_id",
				];
			}
			// check public
			if (empty($this->push_public) && empty($this->push_segment)) {
				return [
					"success" => false,
					"error"   => "No public/segment",
				];
			}
			// check title
			if (empty($this->push_title)) {
				return [
					"success" => false,
					"error"   => "Title required",
				];
			}
			// check message content
			if (empty($this->push_message)) {
				return [
					"success" => false,
					"error"   => "You need to set a message",
				];
			}
			// general push
			$push = [
				"app_id" => $this->app_id,
				"headings" => [$this->app_language => $this->push_title],
				"contents" => [$this->app_language => $this->push_message],
			];
			// add extras
			// -> subtitle
			if (!empty($this->push_subtitle)) $push['subtitle'] = [$this->app_language => $this->push_subtitle];
			// -> url
			if (!empty($this->push_url)) $push['url'] = $this->push_url;
			// -> buttons
			if (!empty($this->push_buttons)) $push['buttons'] = $this->push_buttons;
			// -> segment
			if (!empty($this->push_segment)) $push['included_segments'] = $this->push_segment;
			// -> users
			if (!empty($this->push_public)) $push['include_player_ids'] = $this->push_public;

			return $push;
		}

		/* send push notification */
		public function send() {
			$push = $this->makePush();
			// error making push
			if (isset($push['success']) && !$push['success']) return $push;
			// all ok, continue
			$push = json_encode($push);
			// cURL
	        $ch = curl_init();
	        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
	        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json; charset=utf-8", "Authorization: Basic $this->app_key"]);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	        curl_setopt($ch, CURLOPT_HEADER, FALSE);
	        curl_setopt($ch, CURLOPT_POST, TRUE);
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $push);
	        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

	        $response = curl_exec($ch);
	        $response = json_decode($response);
	        curl_close($ch);
	        // response
	        return $response;
		}
	}
?>
