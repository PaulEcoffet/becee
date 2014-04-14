<?php

namespace Becee\Models;

class BusinessManager
{
	private $pdo = NULL;

	public function __construct($pdo)
	{
		$this->pdo = $pdo;
	}

	public function getBusinessById($business_id)
	{
		$business_req = $this->pdo->prepare 
	}
}