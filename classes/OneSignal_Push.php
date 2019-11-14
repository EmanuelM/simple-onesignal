<?php
	class OneSignal_Push {
		// push parameters
		private $push_title = "";
		private $push_subtitle = "";
		private $push_message = "";
		private $push_url = "";
		private $push_public = [];
		private $push_buttons = [];
		private $push_segment = [];

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
	}
?>