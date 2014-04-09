<?php

// Query database for vehicles registered to current user
$db = new SQLite3('../db/ride_board.db');

// Get current user from cookie
$currentUser = "";
if(isset($_COOKIE['currentUser']))
{
	$currentUser = $_COOKIE['currentUser'];
}

$results = $db->query("SELECT * FROM trips");
$query = $_POST['query']; // TODO use this

$tripData = array();
while ($row = $results->fetchArray())
{
	$vehicleId = $row['vehicle_id'];
	$tripId = $row['trip_id'];
	$seatCount = $db->querySingle("SELECT seat_count FROM vehicles where vehicle_id='{$vehicleId}'");
	$headCount = $db->querySingle("SELECT COUNT(*) as count from trip_users WHERE trip_id='{$tripId}' and request_accepted=1");
	
	$tripData[] = array(
		$tripId,
		$row['origin_loc'],
		$row['destination_loc'],
		date('F d, Y g:i A', $row['departure_date_time']),
		date('F d, Y g:i A', $row['arrival_date_time']),
		$headCount . " / " . $seatCount
	);
}
$tripCount = count($tripData);

// Print JSON output
$output = array(
	"sEcho" => intval($_REQUEST['sEcho']),
	"iTotalRecords" => $tripCount,
	"iTotalDisplayRecords" => $tripCount,
	"aaData" => $tripData
);
echo json_encode($output);
