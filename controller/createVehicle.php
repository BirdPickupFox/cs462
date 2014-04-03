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
$make = $_POST['make'];
$model = $_POST['model'];
$year = $_POST['year'];
$seatCount = $_POST['seatCount'];
$description = $_POST['description'];

// Save vehicle in database
$db = new SQLite3('../db/ride_board.db');
$vehicle = new Vehicle($currentUser, $year, $make, $model, $seatCount, $description);

$statusCode;
$errorMsg;
if($vehicle->error === NULL)
{
	$statusCode = 200;
}
else
{
	$statusCode = 500;
	$errorMsg = $vehicle->error;
}

// Print JSON output
$output = array(
	"statusCode" => $statusCode,
	"errorMsg" => $errorMsg
);
echo json_encode($output);
