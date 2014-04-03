<?php

require_once('../objects/vehicle.php');

// Get current user from cookie
$currentUser = "";
if(isset($_COOKIE['currentUser']))
{
	$currentUser = $_COOKIE['currentUser'];
}
else
{
	echo "You must sign in to register a vehicle";
	die;
}

// Get data from browser
$vehicleId = $_POST['vehicleId'];
$startDateTime = $_POST['startDateTime'];
$endDateTime = $_POST['endDateTime'];
$origin = $_POST['origin'];
$destination = $_POST['destination'];
$price = $_POST['price'];

// TODO Save trip to Google Calendar

// TODO Save trip in database
$db = new SQLite3('../db/ride_board.db');

// TODO add current user as trip_user (request accepted)

