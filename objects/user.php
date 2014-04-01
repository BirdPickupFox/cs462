<?php

class User
{
	public $email;
	public $password;

	public function __construct($email, $password)
	{
		$this->email = $email;
		$this->password = $password;

		$this->createUser();
	}

	private function createUser()
	{
		// TODO check if email is unique, if not throw exception
		// TODO add user to database
	}
}
