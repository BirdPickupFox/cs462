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
		$this->error = NULL;

		$this->createGoogleCalendarEvent();
		$this->createTrip();
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
		
		if(!$result)
		{
			$this->error = $db->lastErrorMsg();
		}
*/
	}

	private function createGoogleCalendarEvent()
	{
		$calendarId = "5hrmsdsdmncm5f0vo3pm37bigo%40group.calendar.google.com";
		$apiKey = "AIzaSyByo6j6i9-kvorsgcw-v8BV1qPgyMdz5XU";

		$body = array();
		$body['end'] = array();
		$body['start'] = array();
		$body['start']['dateTime'] = $this->parseTime($this->start);
		$body['end']['dateTime'] = $this->parseTime($this->end);
		$body['summary'] = $this->origin . " to " . $this->destination;
		$request = json_encode($body);
		$authToken = "ya29.1.AADtN_XYxJ5HtfQMYwq35TtteyyPtXFNmGBjp_mOQj1z98Y-xAmR0MbztNJ8RA";

		$call = curl_init();
		curl_setopt($call, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($call, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($call, CURLOPT_URL, "https://www.googleapis.com/calendar/v3/calendars/$calendarId/events?sendNotifications=false&key=$apiKey");
		curl_setopt($call, CURLOPT_POST, true);
		curl_setopt($call, CURLOPT_POSTFIELDS, $request);
		curl_setopt($call, CURLOPT_HTTPHEADER, array("Content-type: application/json", "Authorization: Bearer " . $authToken));

		$response = curl_exec($call);
		$status = curl_getinfo($call, CURLINFO_HTTP_CODE);
		curl_close($call);

		if($status == 200)
		{
			$json = json_decode($response, true);
			$this->googleCalendarId = $json['id'];
		}
		else
		{
//			$this->error = "Error in Google Calendar ($status): $response"; // Uncomment only if you want errors to be thrown for Google Calendar fails
		}
	}

	private function parseTime($stamp)
	{
		return date('Y-m-d', $stamp) . "T" . date('H:i:sO', $stamp);
	}
}
