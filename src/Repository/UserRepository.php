<?php
namespace App\Repository;

use App\Entity\User;
use App\Repository\database;
class UserRepository{

    public function findByIdentifier(string $identifier):?User{

        $connection= database::connect();
        $query = $connection->prepare('SELECT * FROM users WHERE username=:username OR email=:email');
        $query ->bindValue(':username',$identifier);
        $query ->bindValue(':email',$identifier);
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
        $query->bindValue(':createAt',$user->getcreateAt()->format('Y-m-d H:i:s'));
        $query->execute();

        $user->setId($connection->lastInsertId());
    }
    public function update(User $user) {
        $connection = Database::connect();
        $query = $connection->prepare('UPDATE users (username=:username, email=:email,password=:password,role=:role');
        $query->bindValue(':username',$user->getUsername());
        $query->bindValue(':email', $user->getEmail());
        $query->bindValue(':password', $user->getPassword());
        $query->bindValue(':role', $user->getRole());
        $query->execute();
    }
}