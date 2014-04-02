<?php

// Include necessary object files
require_once('objects/user.php');

// Initialize database
$db = new SQLite3('db/ride_board.db');

// Set up HTML injection
$inject = "";

// Get current user from cookie
$currentUser = "";
if(isset($_COOKIE['currentUser']))
{
	$currentUser = $_COOKIE['currentUser'];
}

// Respond to form actions
if(isset($_REQUEST['formAction']))
{
	$action = $_REQUEST['formAction'];
	
	if($action == "@CREATE_USER")
	{
		$email = $_REQUEST['formEmail'];
		$password = $_REQUEST['formPassword'];
	
		if(filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			$user = new User($email, $password, $db);
			if($user->error === NULL)
			{
				$currentUser = $email;
				setcookie('currentUser', $currentUser, time()+(3600*8), "/");
			}
			else
			{
				myAlert($user->error);
				$currentUser = "";
				setcookie('currentUser', $currentUser, time()-(3600*8), "/");
			}
		}
		else
		{
			myAlert("Error: \"$email\" is not a valid email address");
		}
	}

	else if($action == "@SIGN_IN")
	{
		$email = $_REQUEST['formEmail'];
		$password = $_REQUEST['formPassword'];

		$result = $db->querySingle('SELECT password FROM users WHERE email="' . $email . '"');
		if($result != NULL && $result == $password)
		{
			$currentUser = $email;
			setcookie('currentUser', $currentUser, time()+(3600*8), "/");
		}
		else
		{
			myAlert("Invalid email and/or password");
		}
	}

	else if($action == "@SIGN_OUT")
	{
		$currentUser = "";
		setcookie('currentUser', $currentUser, time()-(3600*8), "/");
	}
}

// Build HTML
include('html/header.php');
include('html/footer.php');

// -------------------------------------- Helper functions

/*
* Creates an alert for the user after the page is initialized
*
* @param str - alert contents (HTML string)
*/
function myAlert($str)
{
	global $inject;
	$inject .=  ("" .
	"<script type='text/javascript'>" .
		"$(document).ready(function() {" .
			"myAlert('$str');" .
		"});" .
	"</script>");
}
