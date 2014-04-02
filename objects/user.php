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
		global $db;
		var_dump($db);
		// TODO check if email is unique, if not throw exception
		$result = $db->querySingle('SELECT * FROM users WHERE email="' . $this->email . '"');
		if($result == NULL)
		{
		// TODO add user to database
			echo "Adding User";
//			This has runtime permission problems
//			$db->exec('INSERT INTO users VALUES("bilbo1","bilbo2")');
//			$db->exec('INSERT INTO users VALUES("' . $this->email . '","' . $this->password . '")');
		}
		else
		{
			//throw exception
			echo "User Exists";
		}
	}
}
