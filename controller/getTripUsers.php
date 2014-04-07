<?php

// Get trip users
$db = new SQLite3('../db/ride_board.db');
require_once('../objects/trip.php');
$trip = Trip::getFromId($_POST['tripId']);
$users = $trip->getMembers();
$driver = $trip->getDriver();

// Format data
$userData = array();
while($row = $users->fetchArray())
{
	$email = $row['user_email'];
	$checked = ($row['request_accepted'] == 1) ? "CHECKED" : "";
	$disabled = "DISABLED";
	if($_POST['canEdit'] == 'true' && $email != $driver)
		$disabled = "";
	$checkBox = "<input type='checkbox' class='tripMemberBox' id='tripMemberBox_$email' $checked $disabled>";

	$userData[] = array(
		$email,
		$checkBox
	);
}
$userCount = count($userData);

// Print JSON output
$output = array(
	"sEcho" => intval($_REQUEST['sEcho']),
	"iTotalRecords" => $userCount,
	"iTotalDisplayRecords" => $userCount,
	"aaData" => $userData
);
echo json_encode($output);
