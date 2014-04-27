<?php
namespace Becee\Models;
class GeneralManager
{
	private $pdo = NULL;

	public function __construct($pdo)
	{
		$this->pdo = $pdo;
	}

    public function getCountries($fields="*")
    {
        $sql = "
        SELECT :fields 
        FROM `countries`
        ;
        ";
        $business_req = $this->pdo->prepare($sql);
        $business_req->bindValue(':fields' => $fields);
        $business_req->execute();
        return($business_req->fetchAll(\PDO::FETCH_ASSOC));
    }

}
