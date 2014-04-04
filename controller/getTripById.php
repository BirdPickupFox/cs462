<?php

$db = new SQLite3('../db/ride_board.db');
require_once('../objects/trip.php');
$trip = Trip::getFromId($_POST['tripId']);
echo json_encode($trip);
