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
		// TODO
		// return Google Calendar event id
	}
}
