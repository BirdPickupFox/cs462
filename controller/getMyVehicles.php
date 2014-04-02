<?php

//TODO replace this test data with real data
$vehicleData = array(
	array(
		1,
		"Toyota",
		"Camry",
		2011,
		5,
		"Large trunk, Pandora, Auxilliary input",
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
