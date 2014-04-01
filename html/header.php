<?php

$currentUser = "";
if(isset($_COOKIE['currentUser']))
{
	$currentUser = $_COOKIE['currentUser'];
}
$currentUser = "fox016@gmail.com";

?>
<!DOCTYPE html>
<html>
<head>
	<link rel='stylesheet' href='css/rideBoard.css'>
	<link rel='stylesheet' href='css/light-theme/jquery-ui-1.10.4.custom.min.css'>
	<script type='text/javascript' src='js/jquery-1.10.2.js'></script>
	<script type='text/javascript' src='js/jquery-ui-1.10.4.custom.min.js'></script>
	<script type='text/javascript' src='js/rideBoard.js'></script>
</head>
<body>

<div id='pageHeader'>
	<table class='wide'>
		<tr>
			<td><img src='images/BYU_name_logo.png'></td>
			<td class='right'>
				<?php
				if($currentUser == "")
				{
				?>
					<button id='signInBtn' onclick='signIn()'>Sign In</button>
					<button id='registerBtn' onclick='register()'>Register</button>
				<?php
				}
				else
				{
				?>
					<?php echo "Hi, $currentUser"; ?>&nbsp;&nbsp;<button id='signInBtn' onclick='signOut()'>Sign Out</button>
				<?php
				}
				?>
			</td>
		</tr>
	</table>
</div>

<table id='boxWrapper' cellspacing='0px'>
	<tr>
		<td id='navWrapper'>
			<table id='navLinkTable' class='wide'>
				<tr>
					<td class='navLink' id='tripNav' onclick='showTrips()'>Trips</td>
				</tr>
				<tr>
					<td class='navLink' id='vehicleNav' onclick='showMyVehicles()'>My Vehicles</td>
				</tr>
			</table>
		</td>
		<td id='viewWrapper'>
			<div id='dynamicContent'>
