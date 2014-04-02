<?php

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
$make = $_POST['make'];
$model = $_POST['model'];
$year = $_POST['year'];
$seatCount = $_POST['seatCount'];
$description = $_POST['description'];

// TODO save vehicle in database. If success, set statusCode to 200. If it fails set to 500 and set error message.
$statusCode = 500;
$errorMsg = "";

// Print JSON output
$output = array(
	"statusCode" => $statusCode,
	"errorMsg" => $errorMsg
);
echo json_encode($output);
