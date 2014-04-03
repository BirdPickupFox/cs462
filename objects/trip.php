<?php

class Trip
{
	public $start;
	public $end;
	public $origin;
	public $destination;
	public $vehicleId;
	public $price;
	public $googleCalendarId;
	public $error;

	public function __construct($start, $end, $origin, $destination, $vehicleId, $price)
	{
		$this->start = $start;
		$this->end = $end;
		$this->origin = $origin;
		$this->destination = $destination;
		$this->vehicleId = $vehicleId;
		$this->price = $price;

		$this->googleCalendarId = $this->createGoogleCalendarEvent();
		$this->error = $this->createTrip();
	}

	public function addUser($userEmail, $requestAccepted)
	{
		// TODO
	}

	private function createTrip()
	{
		return "TODO create trip";
		// TODO
/*
		global $db;
		
		$query = "INSERT INTO vehicles (year, make, model, seat_count, description, owner)
				VALUES({$this->year},'{$this->make}','{$this->model}',{$this->seatCount},'{$this->description}','{$this->owner}')";
		$result = $db->exec($query);
		
		if($result)
		{
			return NULL;
		}
		return $db->lastErrorMsg();
*/
	}

	private function createGoogleCalendarEvent()
	{
		$calendarId = "5hrmsdsdmncm5f0vo3pm37bigo%40group.calendar.google.com";
		$apiKey = "AIzaSyByo6j6i9-kvorsgcw-v8BV1qPgyMdz5XU";

		$request = "{}"; // TODO

		$call = curl_init();
		curl_setopt($call, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($call, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($call, CURLOPT_URL, "https://www.googleapis.com/calendar/v3/calendars/$calendarId/events?sendNotifications=false&key=$apiKey");
		curl_setopt($call, CURLOPT_POST, true);
		curl_setopt($call, CURLOPT_POSTFIELDS, $request);
		curl_setopt($call, CURLOPT_HTTPHEADER, array("Content-type" => "application/json", "Authorization" => "")); // TODO

		$response = curl_exec($call);
	}
}
