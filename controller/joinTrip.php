<?php

require_once('../objects/trip.php');
require_once('../objects/notification.php');

// Get current user from cookie
$currentUser = "";
if(isset($_COOKIE['currentUser']))
{
	$currentUser = $_COOKIE['currentUser'];
}
else
{
	echo "You must sign in to join a trip";
	die;
}

// Setup database
$db = new SQLite3('../db/ride_board.db');

// Get data from browser
$tripId = $_POST['tripId'];

// Get trip from database
$trip = Trip::getFromId($tripId);
$trip->addUser($currentUser, false);
$notification = new Notification($trip->getDriver(), "Attention: $currentUser has requested to join your trip from {$trip->origin} to {$trip->destination}");

// Echo output
$output = array("error" => $trip->error);
echo json_encode($output);
