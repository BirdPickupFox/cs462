<?php

// Get current user from cookie
$currentUser = "";
if(isset($_COOKIE['currentUser']))
{
	$currentUser = $_COOKIE['currentUser'];
}
else
{
	echo "You must sign in to load your notifications";
	die;
}

// Database
$db = new SQLite3('../db/ride_board.db');

$results = $db->query("SELECT * FROM notifications WHERE user_email='$currentUser' ORDER BY created_time desc");

$notifications = array();
while($row = $results->fetchArray())
{
	$notifications[] = array(
		date('Y-m-d g:i A', $row['created_time']),
		$row['text']
	);
}
$notificationCount = count($vehicleData);

// Print JSON output
$output = array(
	"sEcho" => intval($_REQUEST['sEcho']),
	"iTotalRecords" => $notificationCount,
	"iTotalDisplayRecords" => $notificationCount,
	"aaData" => $notifications
);
echo json_encode($output);
