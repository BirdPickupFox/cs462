<?php

class User
{
	public $email;
	public $password;
	public $error;

	public function __construct($email, $password)
	{
		$this->email = $email;
		$this->password = $password;

		$this->error = $this->createUser();
	}
	
	private function createUser()
	{
		global $db;
		$result = $db->querySingle("SELECT * FROM users WHERE email='{$this->email}'");
		if($result == NULL)
		{
			$db->exec("INSERT INTO users VALUES('{$this->email}','{$this->password}')");
			return NULL;
		}
		return "Error: a user with this email address already exists";
	}
}
