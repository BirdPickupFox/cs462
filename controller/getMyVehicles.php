<?php

// Query database for vehicles registered to current user
$db = new SQLite3('../db/ride_board.db');

// Get current user from cookie
$currentUser = "";
if(isset($_COOKIE['currentUser']))
{
	$currentUser = $_COOKIE['currentUser'];
}
else
{
	echo "You must sign in to load your vehicle list";
	die;
}


$results = $db->query("SELECT * FROM vehicles WHERE owner='".$currentUser."'");

$i=0;
$vehicleData = array();
while ($row = $results->fetchArray()) {
	$vehicleData[$i] = array(
		$row['vehicle_id'],
		$row['make'],
		$row['model'],
		$row['year'],
		$row['seat_count'],
		$row['description'],
	);
	$i += 1;
}
$myVehicleCount = count($vehicleData);

// Print JSON output
$output = array(
	"sEcho" => intval($_REQUEST['sEcho']),
	"iTotalRecords" => $myVehicleCount,
	"iTotalDisplayRecords" => $myVehicleCount,
	"aaData" => $vehicleData
);
echo json_encode($output);
