<?php

require_once('notification.php');

class Trip
{
	public $start;
	public $end;
	public $origin;
	public $destination;
	public $vehicleId;
	public $price;
	public $googleCalendarId;
	public $tripId;
	public $error;

	private $authToken = "ya29.1.AADtN_X5gwbtI44nj2RSBArGO8iWpMulJRiQhQhu3RA990FAmc4DQIWsx7teY4M";
	private $calendarId = "5hrmsdsdmncm5f0vo3pm37bigo%40group.calendar.google.com";
	private $apiKey = "AIzaSyByo6j6i9-kvorsgcw-v8BV1qPgyMdz5XU";

	public function __construct()
	{
	}

	public static function getFromId($tripId)
	{
		global $db;

		$result = $db->querySingle("SELECT * FROM trips WHERE trip_id='$tripId'", TRUE);
		if($result !== NULL)
		{
			$instance = new self();
			$instance->start = $result['departure_date_time'];
			$instance->end = $result['arrival_date_time'];
			$instance->origin = $result['origin_loc'];
			$instance->destination = $result['destination_loc'];
			$instance->vehicleId = $result['vehicle_id'];
			$instance->price = $result['total_cost'];
			$instance->googleCalendarId = $result['google_calendar_id'];
			$instance->tripId = $tripId;
			$instance->error = NULL;
		}
		else {
			$instance->error = "Error finding trip (id: $tripId)";
		}

		return $instance;
	}
	
	public static function fromData($start, $end, $origin, $destination, $vehicleId, $price)
	{
		$instance = new self();
		$instance->start = $start;
		$instance->end = $end;
		$instance->origin = $origin;
		$instance->destination = $destination;
		$instance->vehicleId = $vehicleId;
		$instance->price = $price;
		$instance->tripId = NULL;
		$instance->error = NULL;

		$instance->createGoogleCalendarEvent();
		$instance->createTrip();
		return $instance;
	}

	public function getDriver()
	{
		global $db;

		return $db->querySingle("SELECT owner FROM vehicles WHERE vehicle_id='{$this->vehicleId}'");
	}

	public function updateTimes($newStart, $newEnd)
	{
		global $db;

		$result = $db->exec("UPDATE trips SET departure_date_time='$newStart', arrival_date_time='$newEnd' WHERE trip_id='{$this->tripId}'");

		if(!$result)
		{
			$this->error = "Error updating trip times";
		}
		else
		{
			$this->start = $newStart;
			$this->end = $newEnd;

			$departure = $this->parseHumanTime($this->start);
			$arrival = $this->parseHumanTime($this->end);
			$message = "Trip from {$this->origin} to {$this->destination} was updated. Departure will be $departure and arrival will be $arrival.";
			$this->sendNotification($message);

			$this->updateGoogleCalendarEvent();
		}
	}

	public function addUser($userEmail, $requestAccepted)
	{
		global $db;

		$accepted = $requestAccepted ? 1 : 0;

		if($this->tripId != NULL)
		{
			// Make sure there is room in vehicle
			$headCount = $this->getHeadCount();
			$seats = $this->getVehicle();
			$seats = $seats['seat_count'];
			if($headCount >= $seats)
			{
				$this->error = "Error: No room in trip, could not add $userEmail to trip";
				return;
			}

			$isTripUser = $db->querySingle("SELECT COUNT(*) as count FROM trip_users WHERE user_email='$userEmail' AND trip_id='{$this->tripId}'");

			// If user is not already associated with trip, add user to trip
			if($isTripUser == 0)
			{
				$result = $db->exec("INSERT INTO trip_users VALUES('{$userEmail}','{$this->tripId}','{$accepted}')");
			}

			// If user is already associated with trip, update request_accepted flag
			else
			{
				$result = $db->exec("UPDATE trip_users SET request_accepted=$accepted WHERE user_email='$userEmail' AND trip_id='{$this->tripId}'");
			}

			// Check for DB errors
			if(!$result)
			{
				$this->error = "Error: Failed to add user $userEmail to trip";
			}
			else
			{
				// Send notification if request was accepted
				if($requestAccepted)
				{
					$departure = $this->parseHumanTime($this->start);
					$arrival = $this->parseHumanTime($this->end);
					$message = "You have been accepted into a trip from {$this->origin} to {$this->destination}. Departure will be $departure and arrival will be $arrival.";
					$notification = new Notification($userEmail, $message);
				}
			}
		}
		else
		{
			$this->error =  "Error: This trip has not been stored in the database";
		}
	}

	public function removeUser($userEmail)
	{
		global $db;
	
		$result = $db->exec("DELETE FROM trip_users WHERE user_email='$userEmail'");
		if($result)
		{
			$notification = new Notification($this->getDriver(), "Attention: $userEmail has left your trip from {$this->origin} to {$this->destination}");
		}
		else
		{
			$this->error = "Error removing user $userEmail from trip";
		}
	}
	
	public function getHeadCount()
	{
		global $db;
		
		if($this->tripId != NULL)
		{
			$result = $db->querySingle("SELECT COUNT(*) as count from trip_users WHERE trip_id='{$this->tripId}' and request_accepted=1");
			if(is_numeric($result))
				return $result;
		}
		
		return 0;
	}

	public function getMembers()
	{
		global $db;

		return $db->query("SELECT * FROM trip_users WHERE trip_id='{$this->tripId}'");
	}

	public function getVehicle()
	{
		global $db;
		return $db->querySingle("SELECT * FROM vehicles WHERE vehicle_id='{$this->vehicleId}'", true);
	}

	public function cancelTrip()
	{
		global $db;
		$query = "DELETE FROM trips WHERE trip_id='{$this->tripId}'";
		$result = $db->exec($query);
		
		if($result)
		{
			$message = "Trip from {$this->origin} to {$this->destination} was cancelled by the driver.";
			$this->sendNotification($message);
			$this->deleteGoogleCalendarEvent();
		}
		else
		{
			$this->error = $db->lastErrorMsg();
		}
	}

	private function createTrip()
	{
		global $db;
		$query = "INSERT INTO trips (origin_loc, destination_loc, departure_date_time, arrival_date_time, vehicle_id, google_calendar_id,total_cost)
				VALUES('{$this->origin}','{$this->destination}','{$this->start}','{$this->end}','{$this->vehicleId}','{$this->googleCalendarId}','{$this->price}')";
		$result = $db->exec($query);
		
		if($result)
		{
			$this->tripId = $db->lastInsertRowID();
		}
		else
		{
			$this->error = $db->lastErrorMsg();
		}
	}

	private function sendNotification($message)
	{
		global $db;

		$query = "SELECT user_email FROM trip_users WHERE trip_id='{$this->tripId}'";

		$results = $db->query($query);
		while ($row = $results->fetchArray()) {
			$notification = new Notification($row['user_email'], $message);
		}
	}

	private function createGoogleCalendarEvent()
	{
		$body = array();
		$body['end'] = array();
		$body['start'] = array();
		$body['start']['dateTime'] = $this->parseTime($this->start);
		$body['end']['dateTime'] = $this->parseTime($this->end);
		$body['summary'] = "From " . $this->origin . " to " . $this->destination;
		$request = json_encode($body);

		$call = curl_init();
		curl_setopt($call, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($call, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($call, CURLOPT_URL, "https://www.googleapis.com/calendar/v3/calendars/{$this->calendarId}/events?sendNotifications=false&key={$this->apiKey}");
		curl_setopt($call, CURLOPT_POST, true);
		curl_setopt($call, CURLOPT_POSTFIELDS, $request);
		curl_setopt($call, CURLOPT_HTTPHEADER, array("Content-type: application/json", "Authorization: Bearer " . $this->authToken));

		$response = curl_exec($call);
		$status = curl_getinfo($call, CURLINFO_HTTP_CODE);
		curl_close($call);

		if($status == 200)
		{
			$json = json_decode($response, true);
			if(isset($json['id']))
				$this->googleCalendarId = $json['id'];
		}
		else
		{
//			$this->error = "Error in Google Calendar ($status): $response"; // Uncomment only if you want errors to be thrown for Google Calendar fails
		}
	}

	private function getGoogleUpdateSequence()
	{
		$url = "https://www.googleapis.com/calendar/v3/calendars/{$this->calendarId}/events/{$this->googleCalendarId}";
		$call = curl_init();
		curl_setopt($call, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($call, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($call, CURLOPT_URL, $url);
		curl_setopt($call, CURLOPT_HTTPGET, true);
		curl_setopt($call, CURLOPT_HTTPHEADER, array("Authorization: Bearer " . $this->authToken));

		$response = curl_exec($call);
		$status = curl_getinfo($call, CURLINFO_HTTP_CODE);
		curl_close($call);

		$json = json_decode($response, true);
		if(isset($json['sequence']))
			return $json['sequence'];
		return 0;
	}

	private function updateGoogleCalendarEvent()
	{
		$body = array();
		$body['end'] = array();
		$body['start'] = array();
		$body['start']['dateTime'] = $this->parseTime($this->start);
		$body['end']['dateTime'] = $this->parseTime($this->end);
		$body['summary'] = "From " . $this->origin . " to " . $this->destination;
		$body['sequence'] = $this->getGoogleUpdateSequence();
		$request = json_encode($body);

		$url = "https://www.googleapis.com/calendar/v3/calendars/{$this->calendarId}/events/{$this->googleCalendarId}";
		$call = curl_init();
		curl_setopt($call, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($call, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($call, CURLOPT_URL, $url);
		curl_setopt($call, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($call, CURLOPT_POST, 1);
		curl_setopt($call, CURLOPT_POSTFIELDS, $request);
		curl_setopt($call, CURLOPT_HTTPHEADER, array("Content-type: application/json", "Authorization: Bearer " . $this->authToken));

		$response = curl_exec($call);
		$status = curl_getinfo($call, CURLINFO_HTTP_CODE);
		curl_close($call);

		if($status != 200)
		{
//			$this->error = "Error in Google Calendar ($status): $response"; // Uncomment only if you want errors to be thrown for Google Calendar fails
		}
	}

	private function deleteGoogleCalendarEvent()
	{
		$url = "https://www.googleapis.com/calendar/v3/calendars/{$this->calendarId}/events/{$this->googleCalendarId}";
		$call = curl_init();
		curl_setopt($call, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($call, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($call, CURLOPT_URL, $url);
		curl_setopt($call, CURLOPT_CUSTOMREQUEST, 'DELETE');
		curl_setopt($call, CURLOPT_HTTPHEADER, array("Authorization: Bearer " . $this->authToken));

		$response = curl_exec($call);
		$status = curl_getinfo($call, CURLINFO_HTTP_CODE);
		curl_close($call);

		if($status != 200)
		{
//			$this->error = "Error in Google Calendar ($status): $response"; // Uncomment only if you want errors to be thrown for Google Calendar fails
		}
	}

	private function parseTime($stamp)
	{
		return date('Y-m-d', $stamp) . "T" . date('H:i:sO', $stamp);
	}

	private function parseHumanTime($stamp)
	{
		return date('m-d-Y', $stamp) . " at " . date('g:i', $stamp);
	}
}
