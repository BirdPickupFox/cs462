<?php

$db = new SQLite3('../db/ride_board.db');
require_once('../objects/trip.php');
require_once('/opt/webroot/util/byuUtil.php');

// Pull changes from Google Calendar
$tripIds = $db->query("SELECT trip_id FROM trips WHERE google_calendar_id<>''");
while($row = $tripIds->fetchArray())
{
	$trip = Trip::getFromId($row['trip_id']);
	$googleEvent = $trip->getGoogleEvent();

	$googleEvent = json_decode($googleEvent, true);
	if($googleEvent !== NULL)
	{
		if(isset($googleEvent['start']['dateTime']) && isset($googleEvent['end']['dateTime']))
		{
			$start = strtotime($googleEvent['start']['dateTime']);
			$end = strtotime($googleEvent['end']['dateTime']);
			$trip->updateTimes($start, $end, false);
		}
	}
}

// Return success
echo "200";
