<?php

// Get current user from cookie
$currentUser = "";
if(isset($_COOKIE['currentUser']))
{
	$currentUser = $_COOKIE['currentUser'];
}

// Get data from browser
$startDate = getTimeString($_POST['startDate']);
$endDate = getTimeString($_POST['endDate']);
$onlyMine = $_POST['onlyMine'];
$origin = $_POST['origin'];
$destination = $_POST['destination'];

//select * from trips where origin_loc="Provo, UT" and destination_loc="San Diego, CA"
global $db;
//here are the results for origin and destination, do we want to search
//for the exact date range? Or add 1 - 3 day leniance on each end?
$result = $db->query("SELECT * FROM trips WHERE origin_loc='{$origin}' and destination_loc='{$destination}'");
$output = array();

//I don't know the exact syntax, Nate, could you test to see if this is correct?
//TODO: double check this syntax
while ($row = $results->fetchArray()) {
    array_push($output,
    	array(
    		'id' => $row['trip_id'],
    		'title' => 'Need Trip Name Here',
    		'allDay' => false,
    		'start' => $row['departure_date_time'],
    		'end' => $row['arrival_date_time'],
    		'color' => "#F6F68D",
    		'className' => "trip_item",
    		'editable' => true,
    		'textColor' => "black"
    	)
    );
}

//$output = array(
//	array(
//		'id'=> "testid", // unique trip id
//		'title'=> "Test Trip", // title ("[origin] to [destination]" or something like that)
//		'allDay'=> false,
//		'start'=> "04/04/2014 10:30 AM", // start datetime
//		'end'=> "04/04/2014 11:30 PM", // end datetime
//		'color'=> "#F6F68D",
//		'className'=> "trip_item",
//		'editable'=> true,
//		'textColor'=> "black",
//	)
//);
echo json_encode($output);

/*
 * Returns Y-m-dTH:i:s0 format of given timestamp
 */
function getTimeString($time)
{
	return date("Y-m-d", $time) . "T" . date("H:i:s", $time);
}
