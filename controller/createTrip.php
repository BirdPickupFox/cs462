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
$vehicleId = $_POST['vehicleId'];
$startDateTime = $_POST['startDateTime'];
$endDateTime = $_POST['endDateTime'];
$origin = $_POST['origin'];
$destination = $_POST['destination'];
$price = $_POST['price'];

// Add trip to database
$trip = new Trip($startDateTime, $endDateTime, $origin, $destination, $vehicleId, $price);
$trip->addUser($currentUser, true);
if($trip->error !== NULL)
{
	echo "Error creating trip: " . $trip->error;
}

// Echo output
$output = array("tripId" => $trip->tripId);
echo json_encode($output);
