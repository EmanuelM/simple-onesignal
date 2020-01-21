<?php
	namespace OneSignal;

	class Notification
	{
		// push parameters
		public $title    = "";
		public $subtitle = "";
		public $message  = "";
		public $url      = "";
		public $public   = [];
		public $buttons  = [];
		public $segment  = [];

		/**
		 * Add a user to send
		 * @param string $player_id - OneSignal player id
		 */
		public function addUser(string $player_id = "")
		{
			array_push($this->public, $player_id);
		}

		/**
		 * Set push title
		 * @param string $title
		 */
		public function setTitle(string $title = "")
		{
			$this->title = $title;
		}

		/**
		 * Set push subtitle. Only web browsers
		 * @param string $subtitle
		 */
		public function setSubtitle(string $subtitle = "")
		{
			$this->subtitle = $subtitle;
		}

		/**
		 * Set push message
		 * @param string $message
		 */
		public function setMessage(string $message = "")
		{
			$this->message = $message;
		}

		/**
		 * Set callback url
		 * @param string $url
		 */
		public function setUrl(string $url = "")
		{
			$this->url = $url;
		}

		/**
		 * Add button
		 * @param string $id
		 * @param string $text
		 */
		public function addButton($id = "", string $text = "")
		{
			array_push($this->buttons, ["id" => $id, "text" => $text]);
		}

		/**
		 * Add a segment to send
		 * @param string $segment
		 */
		public function addSegment(string $segment = "")
		{
			array_push($this->segment, $segment);
		}
	}
?>