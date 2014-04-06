<?php
require_once('../objects/trip.php');
require_once('../objects/notification.php');

class User
{
	public $email;
	public $password;
	public $error;

	public function __construct($email, $password)
	{
		$this->email = $email;
		$this->password = $password;

		$this->error = $this->createUser();
	}

	private function createUser()
	{
		global $db;
		$result = $db->querySingle("SELECT * FROM users WHERE email='{$this->email}'");
		if($result == NULL)
		{
			$db->exec("INSERT INTO users VALUES('{$this->email}','{$this->password}')");
			return NULL;
		}
		return "Error: a user with this email address already exists";
	}
	
	public function acceptUser($riderEmail, $tripId)
	{
		global $db;
		$tempRider = new self($riderEmail, "");
		$result = $db->querySingle("SELECT * FROM trips WHERE trip_id='$tripId'", TRUE);
		if($result !== NULL)
		{
			$trip = new Trip();
			$trip->start = $result['departure_date_time'];
			$trip->end = $result['arrival_date_time'];
			$trip->origin = $result['origin_loc'];
			$trip->destination = $result['destination_loc'];
			$trip->vehicleId = $result['vehicle_id'];
			$trip->price = $result['total_cost'];
			$trip->googleCalendarId = $result['google_calendar_id'];
			$trip->tripId = $tripId;
			$trip->error = NULL;
			$trip->addUser($riderEmail, true);
			
			//TODO check for overflow of seating
			$tempRider->sendNotification("You have been accepted!");
		}
	}
	
	//ie. Send driver that someone has requested to join trip
	//send rider that driver has accepted their request
	public function sendNotification($message)
	{
		$this->emailNotification($message);
	}
	
	private function emailNotification($message)
	{
		$notification = new Notification($this->email, $message);
		$notification->sendEmail();
		
	}
}
