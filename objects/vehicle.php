<?php

class Vehicle
{
	public $owner;
	public $year;
	public $make;
	public $model;
	public $seatCount;
	public $description;
	public $error;

	public function __construct($owner, $year, $make, $model, $seatCount, $description)
	{
		$this->owner = $owner;
		$this->year = $year;
		$this->make = $make;
		$this->model = $model;
		$this->seatCount = $seatCount;
		$this->description = $description;

		$this->error = $this->createVehicle();
	}

	private function createVehicle()
	{
		global $db;
		
		$query = "INSERT INTO vehicles VALUES(null,{$this->year},'{$this->make}','{$this->model}',{$this->seatCount},'{$this->description}','{$this->owner}')";
		$result = $db->exec($query);
		
		if ($result)
		{
			return NULL;
		}
		return "Error: a user with this email address already exists";
	}
}
