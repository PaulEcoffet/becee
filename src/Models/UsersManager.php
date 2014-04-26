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
		return($user_req->fetch());
	}
    public function insertUser($user)
    {
        $sql = "INSERT INTO `users` (name, email, hashed_password,  salt)
		        VALUES(:name, :email, :hashed_password, :salt)
		        ;
                ";
        
        $business_req = $this->pdo->prepare($sql); 
        $business_req->bindValue(':name', $user['name'],\PDO::PARAM_STR);
        $business_req->bindValue(':email', $user['email'],\PDO::PARAM_STR);
        $business_req->bindValue(':hashed_password', $user['password'],\PDO::PARAM_STR);
        $business_req->bindValue(':salt', 'test',\PDO::PARAM_STR);
        $business_req->execute();

        $sql = "SELECT * FROM `users` WHERE id = (SELECT MAX(id) FROM `users`);";
        
        $business_req = $this->pdo->prepare($sql);
        $business_req->execute();

        return $business_req->fetch(\PDO::FETCH_ASSOC);
    }
    public function insertUserAvatar($user_id, $avatar_path)
    {
    	echo $user_id,$avatar_path;
        $sql = "UPDATE `users`
				SET avatar_path=:avatar_path
				WHERE id=:user_id; 
		        ;
                ";
        
        $business_req = $this->pdo->prepare($sql); 
        $business_req->bindValue(':avatar_path', $avatar_path,\PDO::PARAM_STR);
        $business_req->bindValue(':user_id', $user_id,\PDO::PARAM_INT);
        $business_req->execute();

    }

    public function createDummyUser()
    {
        //TODO: Create a user without any information and with category 'Dummy'
        return (object) array('id' => 1); //return a User entity with the dummyuser information.
    }
}
