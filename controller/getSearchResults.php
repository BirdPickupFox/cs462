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


$results = $db->query("SELECT * FROM trips");

$i=0;
$vehicleData = array();
while ($row = $results->fetchArray()) {
	$vehicleId = $row['vehicle_id'];
	$seatCount = $db->querySingle("SELECT seat_count FROM vehicles where vehicle_id='{$vehicleId}'");
	
	$vehicleData[$i] = array(
		$row['trip_id'],
		$row['origin_loc'],
		$row['destination_loc'],
		date('F d, Y', $row['departure_date_time']),
		date('F d, Y', $row['arrival_date_time']),
		$seatCount
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
