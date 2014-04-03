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

// TODO Query database for trips and replace this test data with real data
$output = array(
	array(
		'id'=> "testid", // unique trip id
		'title'=> "Test Trip", // title ("[origin] to [destination]" or something like that)
		'allDay'=> false,
		'start'=> "04/04/2014 10:30 AM", // start datetime
		'end'=> "04/04/2014 11:30 PM", // end datetime
		'color'=> "#F6F68D",
		'className'=> "trip_item",
		'editable'=> true,
		'textColor'=> "black",
	)
);
echo json_encode($output);

/*
 * Returns Y-m-dTH:i:s0 format of given timestamp
 */
function getTimeString($time)
{
	return date("Y-m-d", $time) . "T" . date("H:i:s", $time);
}
