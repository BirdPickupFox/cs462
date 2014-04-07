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
	echo "You must sign in to save trip members";
	die;
}

// Setup database
$db = new SQLite3('../db/ride_board.db');

// Get data from browser
$tripId = $_POST['tripId'];
$memberList = json_decode($_POST['members'], true);

// Get trip from database
$trip = Trip::getFromId($tripId);

// If current user is not trip owner, throw error
$driver = $trip->getDriver();
if($driver != $currentUser)
{
	echo "Only the driver ($driver) can save users for this trip";
	die;
}

// Save trip users
foreach($memberList as $member)
{
	$accepted = ($member['accepted'] == "true");
	$trip->addUser($member['email'], $accepted);
}

// Echo output
$output = array("error" => $trip->error);
echo json_encode($output);
