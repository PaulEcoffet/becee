<?php
namespace Becee\Models;
class UsersManager
{
	private $PDO = NULL;

	public function __construct($PDO)
	{
		$this->pdo = $PDO;
	}

	public function getUserById($user_id)
	{
		$user_req = $PDO->prepare('SELECT * FROM Users WHERE id = ?');
		$user_req->execute($user_id);

		return($user_req->fetch());
	}
	public function getUserbyMail($user_mail)
	{
		$user = $PDO->prepare('SELECT * FROM Users WHERE name = ?');
		$user_req->execute($user_name);
		return($user_req->fetch());
	}

	public function checkValidAuth($user_email, $password)
	{
		$user_req = $PDO->prepare('SELECT * FROM Users WHERE email = ? AND  hashed_password = SHA1(CONCAT(?, salt))');
		$user_req->execute($user_email, $password);
		return($user_req->fetch())
	}

}


