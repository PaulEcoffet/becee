<?php

namespace Becee\Models;

class BusinessManager
{
	private $pdo = NULL;

	public function __construct($pdo)
	{
		$this->pdo = $pdo;
	}

	public function getBusinessByIdWithoutManager($business_id)
	{
		$business_req = $this->pdo->prepare('SELECT id, name, longitude, latitude, website FROM Businesses WHERE id = ?');
		$business_req->execute($business_id);
		return($business_req->fetch());
	}

	public function getBusinessByIdWithManager($business_id)
	{
		$business_req = $this->pdo->prepare('SELECT * FROM Businesses INNER JOIN Users ON id_manager = user.id WHERE businesses.id = ?');
		$business_req->execute($business_id);
		return($business_req->fetch());
	}
}