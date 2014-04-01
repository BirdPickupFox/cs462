<?php

// Include necessary object files
require_once('objects/user.php');

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

		$user = new User($email, $password);

		// TODO only do this if creating User doesn't throw exception
		$currentUser = $user->email;
		setcookie('currentUser', $currentUser, time()+(3600*8), "/");
	}

	else if($action == "@SIGN_IN")
	{
		$email = $_REQUEST['formEmail'];
		$password = $_REQUEST['formPassword'];

		// TODO sign in
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
