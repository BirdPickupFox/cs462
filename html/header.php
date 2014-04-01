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
	<table class='wide' id='headerTable'>
		<tr>
			<td><img src='images/BYU_name_logo.png'></td>
			<td class='right'>
				<?php
				if($currentUser == "")
				{
				?>
					<button id='signInBtn'>Sign In</button>
				<?php
				}
				else
				{
				?>
					<?php echo "Hi, $currentUser"; ?>&nbsp;&nbsp;<button id='signInBtn'>Sign Out</button>
				<?php
				}
				?>
			</td>
		</tr>
	</table>
</div>
