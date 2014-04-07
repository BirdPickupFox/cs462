<?php

require_once('../objects/trip.php');

// Get current user from cookie
$currentUser = "";
if(isset($_COOKIE['currentUser']))
{
	$currentUser = $_COOKIE['currentUser'];
}
else
{
	echo "You must sign in to create a trip";
	die;
}

// Setup database
$db = new SQLite3('../db/ride_board.db');

// Get data from browser
$tripId = $_POST['tripId'];
$startTime = $_POST['startTime'];
$endTime = $_POST['endTime'];

// Get trip from database
$trip = Trip::getFromId($tripId);

// If current user is not trip owner, throw error
$driver = $trip->getDriver();
if($driver != $currentUser)
{
	echo "Only the driver ($driver) can update this trip";
	die;
}

// Update trip
$trip->updateTimes($startTime, $endTime, true);

// Echo output
$output = array("error" => $trip->error);
echo json_encode($output);
