<?php

$vehicleId = $_POST['vehicleId'];
$db = new SQLite3('../db/ride_board.db');
$results = $db->querySingle("SELECT * FROM vehicles WHERE vehicle_id='$vehicleId'", true);
echo json_encode($results);
