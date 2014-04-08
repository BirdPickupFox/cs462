<?php

$clientId = "861683707302.apps.googleusercontent.com";
$redirectUri = "https://saasta-dev.byu.edu/noauth/test/cs462/oauth.php";

if(isset($_GET['error']))
{
	echo "Error: " . $_GET['error'] . ": " . $_GET['state'];
	die;
}
if(isset($_GET['code']))
{
	$code = $_GET['code'];
	$clientSecret = "_T2NG-7ngaLfPFnRDd-RTdAE";
	$url = "https://accounts.google.com/o/oauth2/token";

	$request = "code=$code&client_id=$clientId&client_secret=$clientSecret&redirect_uri=$redirectUri&grant_type=authorization_code";
	
	$call = curl_init($url);
	curl_setopt($call, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($call, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($call, CURLOPT_POST, true);
	curl_setopt($call, CURLOPT_POSTFIELDS, $request);
	curl_setopt($call, CURLOPT_HTTPHEADER, array("Content-type" => "application/x-www-form-urlencoded"));

	$response = curl_exec($call);
	$callInfo = curl_getinfo($call);
	curl_close($call);
	$json = json_decode($response, true);
	if($json === NULL)
	{
		echo "Error: Could not get oauth token.<br>";
		echo "URL: $url<br>";
		echo "Response: $response<br>";
		echo json_encode($callInfo) . "<br>";
		die;
	}
	$authToken = $json['access_token'];
	echo "Token: $authToken";

	$db = new SQLite3('db/ride_board.db');
	$result = $db->exec("UPDATE auth SET token='$authToken' WHERE auth_id=1");
	if(!$result)
	{
		echo "<br><br>Error: " . $db->lastErrorMsg();
	}
/*
	$calendarId = "5hrmsdsdmncm5f0vo3pm37bigo%40group.calendar.google.com";
	$url = "https://www.googleapis.com/calendar/v3/calendars/$calendarId/events/watch";

	$body = array();
	$body['id'] = "2902385-aacdegge-232543-000431";
	$body['type'] = "web_hook";
	$body['address'] = "https://saasta-dev.byu.edu/noauth/test/cs462/controller/notifications.php";
	$request = json_encode($body);

	$call = curl_init();
	curl_setopt($call, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($call, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($call, CURLOPT_URL, $url);
	curl_setopt($call, CURLOPT_POST, true);
	curl_setopt($call, CURLOPT_POSTFIELDS, $request);
	curl_setopt($call, CURLOPT_HTTPHEADER, array("Content-type: application/json", "Authorization: Bearer " . $authToken));

	$response = curl_exec($call);
	$status = curl_getinfo($call, CURLINFO_HTTP_CODE);
	curl_close($call);

	echo "<br><br>";
	echo $response;
*/
}

?>
<!DOCTYPE html>
<html>
<head>
<script type='text/javascript' src='js/jquery-1.10.2.js'></script>
<script type='text/javascript'>

function connect()
{
	window.location.href = "https://accounts.google.com/o/oauth2/auth" +
				"?response_type=code" +
				"&client_id=<?php echo $clientId;?>" +
				"&redirect_uri=" + encodeURIComponent("<?php echo $redirectUri;?>") +
				"&scope=" + encodeURIComponent("https://www.googleapis.com/auth/calendar");
				
}

</script>
</head>
<body>
	<button onclick='connect()'>Connect</button>
</body>
</html>
