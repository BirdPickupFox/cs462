<?php

// Get current user from cookie
$currentUser = "";
if(isset($_COOKIE['currentUser']))
{
	$currentUser = $_COOKIE['currentUser'];
}

// Get data from browser
$startDate = $_POST['startDate'];
$endDate = $_POST['endDate'];
$onlyMine = $_POST['onlyMine'];
$origin = $_POST['origin'];
$destination = $_POST['destination'];

// Setup database
$db = new SQLite3('../db/ride_board.db');

$result = $db->query("SELECT * FROM trips"); // TODO add parameters as needed
$output = array();

while ($row = $result->fetchArray()) {
	$output[] = array(
    		'id' => $row['trip_id'],
    		'title' => "From {$row['origin_loc']} to {$row['destination_loc']}",
    		'allDay' => false,
    		'start' => $row['departure_date_time'],
    		'end' => $row['arrival_date_time'],
    		'color' => "#F6F68D",
    		'className' => "trip_item",
    		'editable' => true,
    		'textColor' => "black"
    	);
}

echo json_encode($output);
