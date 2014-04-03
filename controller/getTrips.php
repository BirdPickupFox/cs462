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

// TODO Query database for trips
$db = new SQLite3('../db/ride_board.db');

echo "TODO get trips";
