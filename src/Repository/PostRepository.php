<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\User;

class PostRepository{
    public function persistPost(Post $post){
        $connection=database::connect();
        $query = $connection->prepare('INSERT INTO posts (content,respondTo, postedAt,author_id) VALUES (:content,:respondTo, :postedAt,:author_id)');
        $query->bindValue(':content',$post->getContent());
        $query->bindValue(':author_id', $post->getAuthor()->getId());
        $query->bindValue(':postedAt',$post->getPostedAt()->format('Y-m-d H:i:s'));
        if($post->getRespondTo()==null && $post->getId()!=null){
            $query->bindValue(':respondTo',null);
        }else{
            $query->bindValue(':respondTo',$post->getId());
        }
        
        $query->execute();
        $post->setId($connection->lastInsertId());

    }
    public function findAll(int $offset,int $limite ){
        $connection=database::connect();
        $query = $connection->prepare(
            
        "SELECT * FROM posts 
        INNER JOIN users ON author_id=userId
        ORDER BY postedAt DESC
        LIMIT $offset,$limite");

        $query->execute();
        $post_list=[];
        foreach($query->fetchAll() as $line){
            $user=new User();
            $user->setId($line['userId']);
            $user->setUsername($line['username']);
            $post=new Post();
            $post->setId($line['postId']);
            $post->setContent($line['content']);
            $post->setAuthor($user); 
            $post->setPostedAt(new \DateTimeImmutable($line['postedAt']));
            $post_list[]=$post;
        }
       return $post_list;
    }
    public function postByusername(int $offset,int $limit,string $username){
        $connection=database::connect();
        $query = $connection->prepare(
            "SELECT * FROM users 
            INNER JOIN posts ON author_id=userId
            WHERE username=:username
            ORDER BY `postedAt` DESC
            LIMIT $offset,$limit");

            $query->bindValue(":username", $username);
            $query->execute();
            $post_list_by_id=[];
            foreach($query->fetchAll() as $line){
                $user=new User();
                $user->setId($line['userId']);
                $user->setUsername($line['username']);
                $post=new Post();
                $post->setId($line['postId']);
                $post->setContent($line['content']);
                $post->setAuthor($user);
                $post->setPostedAt(new \DateTimeImmutable($line['postedAt']));
                $post_list_by_id[]=$post;
        }
        return $post_list_by_id;
    }

}