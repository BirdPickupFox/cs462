<?php

$headers = getallheaders();

require_once('../objects/notification.php');
$db = new SQLite3('../db/ride_board.db');
$results = $db->query("SELECT email FROM users");
while($row = $results->fetchArray())
{
	$n = new Notification($row['email'], "Warning: Trip was modified from Google Calendar");
}

echo "200";
