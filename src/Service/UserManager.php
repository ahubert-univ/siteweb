<?php

require_once __DIR__ . "/../Model/User.php";
require_once __DIR__ . "/../Service/Storage.php";
require_once __DIR__ . "/../Service/ValidationData.php";
readonly class UserManager
{
    private Storage $storage;
    public function __construct(){
        $this->storage = new Storage('db','my_user','my_user','my_password');
    }

    public function verificationData(User $user):bool
    {
        $msg = match (false) {
            ValidationData::stringNotEmpty($user->getUsername()) => 'Username can not be empty',
            ValidationData::verificationEmail($user->getEmail()) => 'Your Email is incorrect ',
            ValidationData::verificationPassword($user->getPassword()) => 'Your password is incorrect ! ',
            !$this->existUsers($user->getEmail())  => 'You\'re already register please login',
            default => true,
        };

        if(true !== $msg){
            $_SESSION['form_msg'] = $msg;
            $msg = false;
        }

        return $msg;

    }

    public function loggedUser(string $email,string $password): bool
    {
        $user = $this->existUsers($email);

        return $user instanceof User && password_verify($password, $user->getPassword());
    }

    public function mappedUser($username,$email,$password):User
    {
        $objUser = new User();
        $objUser->setEmail($email);
        $objUser->setUsername($username);
        $objUser->setPassword($password);

        return $objUser;
    }

    public function insertUser(User $user):bool
    {
        $query = "INSERT INTO users (username, password, email) VALUES (:username, :password, :email)";
        return $this->queryPrepare($query,$user)->execute();
    }

    public function updateUser(User $user):bool
    {
        $query = "UPDATE users SET username = :username,password =:password,email=:email  WHERE id =:id";

        $stmt = $this->queryPrepare($query,$user);
        $id = $user->getId();
        $stmt->bindParam('id', $id);
        return $stmt->execute();
    }

    private function queryPrepare(string $query,User $user):\PDOStatement|false{
        $pdo = $this->storage->connexion();
        $stmt = $pdo->prepare($query);
        $username = $user->getUsername();
        $stmt->bindParam(':username', $username);
        $password = $user->getPassword();
        $stmt->bindParam(':password', $password);
        $email = $user->getEmail();
        $stmt->bindParam('email', $email);
        return $stmt;
    }
    public function selectUsers(string $email): array
    {

        $pdo = $this->storage->connexion();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email= :email");
        $stmt->bindParam(':email',$email);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_CLASS, "User");
    }

    public function existUsers(string $email): User|false
    {
        $pdo = $this->storage->connexion();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email= :email");
        $stmt->bindParam(':email',$email);
        $stmt->execute();

        return $stmt->fetchObject("User") ;
    }
}