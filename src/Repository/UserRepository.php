<?php
namespace App\Repository;

use App\Entity\User;
use App\Repository\database;
class UserRepository{

    public function findByUsername(string $username):?User{

        $connection= database::connect();
        $query = $connection->prepare('SELECT * FROM users WHERE username=:username');
        $query ->bindValue(':username',$username);
        $query->execute();

        if($line = $query->fetch()){
            $user=new User();
            $user->setId($line['userId']);
            $user->setEmail($line['email']);
            $user->setPassword($line['password']);
            $user->setUsername($line['username']);
            $user->setRole($line['role']);
            
            return $user; 
        }
        return null;
    }
    public function persist(User $user) {
        $connection = Database::connect();
        $query = $connection->prepare('INSERT INTO users (username, email,password,role,createAt) VALUES (:username, :email,:password,:role,:createAt)');
        $query->bindValue(':username',$user->getUsername());
        $query->bindValue(':email', $user->getEmail());
        $query->bindValue(':password', $user->getPassword());
        $query->bindValue(':role', $user->getRole());
        $query->bindValue(':createAt',$user->getcreateAt()->format('Y-m-d H:m:s'));
        $query->execute();

        $user->setId($connection->lastInsertId());
    }
}