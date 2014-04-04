<?php

// Get current user from cookie
$currentUser = "";
if(isset($_COOKIE['currentUser']))
{
	$currentUser = $_COOKIE['currentUser'];
}

// Get trip
$db = new SQLite3('../db/ride_board.db');
require_once('../objects/trip.php');
$trip = Trip::getFromId($_POST['tripId']);

// Set driver flag
if($trip->getDriver() == $currentUser)
{
	$trip = (array) $trip;
	$trip['isDriver'] = true;
}
else
{
	$trip = (array) $trip;
	$trip['isDriver'] = false;
}

echo json_encode($trip);
