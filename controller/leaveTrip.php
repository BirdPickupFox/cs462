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
	echo "You must sign in to leave a trip";
	die;
}

// Setup database
$db = new SQLite3('../db/ride_board.db');

// Get data from browser
$tripId = $_POST['tripId'];

// Get trip from database
$trip = Trip::getFromId($tripId);
$trip->removeUser($currentUser);

// Echo output
$output = array("error" => $trip->error);
echo json_encode($output);
