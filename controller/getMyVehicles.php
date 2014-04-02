<?php

// Get current user from cookie
$currentUser = "";
if(isset($_COOKIE['currentUser']))
{
	$currentUser = $_COOKIE['currentUser'];
}
else
{
	echo "You must sign in to load your vehicle list";
	die;
}

// TODO replace this test data with real data in same format
$vehicleData = array(
	array(
		1, 		// unique id
		"Toyota", 	// make
		"Camry", 	// model
		2011, 		// year
		5, 		// seat count
		"Large trunk, Pandora, Auxilliary input", // description
	),
	array(
		2,
		"Dodge",
		"Ram",
		2004,
		5,
		"Truck, large bed, not much leg room in back",
	),
);
$myVehicleCount = count($vehicleData);

// Print JSON output
$output = array(
	"sEcho" => intval($_REQUEST['sEcho']),
	"iTotalRecords" => $myVehicleCount,
	"iTotalDisplayRecords" => $myVehicleCount,
	"aaData" => $vehicleData
);
echo json_encode($output);
