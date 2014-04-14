<?php
namespace Becee\Models;
class UsersManager
{
	private $pdo = NULL;

	public function __construct($pdo)
	{
		$this->pdo = $pdo;
	}

	public function getUserById($user_id)
	// return the user corresponding to the parameters $user_id (id in our database Users), 
	{
		$user_req = $this->pdo->prepare('SELECT * FROM Users WHERE id = ?');
		$user_req->execute($user_id);

		return($user_req->fetch());
	}
	public function getUserbyMail($user_mail)
	// return the user corresponding to the parameter $user_email (email in our databse Users )
	{
		$user = $this->pdo->prepare('SELECT * FROM Users WHERE name = ?');
		$user_req->execute($user_name);
		return($user_req->fetch());
	}

	public function checkValidAuth($user_email, $password)
	// return the user corresponding to the parameter $user_mail, if the password correspond, else FALSE is returned
	{
		$user_req = $this->pdo->prepare('SELECT * FROM Users WHERE email = ? AND  hashed_password = SHA1(CONCAT(?, salt))');
		$user_req->execute($user_email, $password);
		return($user_req->fetch())
	}

}
