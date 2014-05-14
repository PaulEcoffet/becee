<?php
namespace Becee\Models;
class UsersManager
{
    private $pdo = NULL;
    protected $app;

    public function __construct(\QDE\App $app)
    {
        $this->app = $app;
        $this->pdo = $this->app->getPdo();
    }

    public function getUserById($user_id)
    // return the user corresponding to the parameters $user_id (id in our database Users),
    {
        $user_req = $this->pdo->prepare('SELECT * FROM users WHERE id = ?');
        $user_req->execute($user_id);

        return($user_req->fetch());
    }

    public function getLastestUsers($limit=5)
    // return the newest users
    {
        $user_req = $this->pdo->prepare('SELECT * FROM users WHERE users.category <> 4 ORDER BY users.id DESC LIMIT :limit;');
        $user_req->bindValue(':limit',$limit, \PDO::PARAM_INT);
        $user_req->execute();

        return($user_req->fetchAll(\PDO::FETCH_ASSOC));
    }

    public function getUserByMail($user_mail)
    // return the user corresponding to the parameter $user_email (email in our databse Users )
    {
        $user_req = $this->pdo->prepare('SELECT * FROM users WHERE email = ?');
        $user_req->execute(array($user_mail));
        return($user_req->fetch());
    }

    public function checkValidAuth($user_email, $password)
    // return the user corresponding to the parameter $user_mail, if the password correspond, else FALSE is returned
    {
        $user_req = $this->pdo->prepare('SELECT COUNT(id) nbr, id, hashed_password, firstname, lastname FROM users WHERE email = ? AND hashed_password = SHA1(CONCAT(?, salt))');
        $user_req->execute(array($user_email, $password));
        $result = $user_req->fetch();
        if ($result['nbr'] == 1) {
            return($result);
        }
    }

    public function insertUser($user, $category=1)
    {
        $sql = "INSERT INTO `users` (firstname, lastname, email, hashed_password, salt, inscription_time, last_visit_time, category)
                VALUES(:firstname, :lastname, :email, SHA1(CONCAT(:hashed_password, :salt)), :salt, NOW(), NOW(), :category)
                ;
                ";

        $business_req = $this->pdo->prepare($sql);
        $business_req->bindValue(':firstname', $user['firstname'],\PDO::PARAM_STR);
        $business_req->bindValue(':lastname', $user['lastname'],\PDO::PARAM_STR);
        $business_req->bindValue(':email', $user['email'],\PDO::PARAM_STR);
        $business_req->bindValue(':hashed_password', $user['password'],\PDO::PARAM_STR);
        $business_req->bindValue(':salt', uniqid(),\PDO::PARAM_STR);
        $business_req->bindValue(':category', $category,\PDO::PARAM_INT);
        $business_req->execute();

        //TODO Very ugly, must find a better solution!
        $sql = "SELECT * FROM `users` WHERE id = (SELECT MAX(id) FROM `users`);";

        $business_req = $this->pdo->prepare($sql);
        $business_req->execute();

        return $business_req->fetch(\PDO::FETCH_ASSOC);
    }

    public function createDummyUser()
    {
        //TODO: Create a user without any information and with category 'Dummy'
        return $this->insertUser(array('firstname' => NULL, 'lastname' => NULL, 'email' => NULL, 'password' => NULL), 4);
    }
}
