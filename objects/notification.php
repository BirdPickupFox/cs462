<?php

class Notification
{
	private $email;
	private $text;	

	public function __construct($email, $text)
	{
		$this->email = $email;
		$this->text = $text;

		$this->sendEmail();
		$this->createNotification();
	}

	private function sendEmail()
	{
		@mail($this->email, "Trip Update Notification", $this->text, "From: catch_ride_no_reply@byu.edu\r\n\r\n");
	}

	private function createNotification()
	{
		global $db;
		$created = time();
		$db->exec("INSERT INTO notifications VALUES ('{$this->email}', '{$this->text}', $created)");
	}
}
