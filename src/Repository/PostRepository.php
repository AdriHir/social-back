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
        $query->bindValue(':respondTo',null);
        $query->execute();
        $post->setId($connection->lastInsertId());

    }
    public function findAll(int $offset,int $limit ){
        $connection=database::connect();
        $query = $connection->prepare(
            
        'SELECT * FROM posts 
        INNER JOIN users ON author_id=userId
        ORDER BY postedAt DESC
        LIMIT $offset,$limit ');

        $query->bindValue(':limit',$limit);
        $query->bindValue(':offset',$offset);
        $query->execute();
        $post_list=[];
        foreach($query->fetchAll() as $post_list){
            $user=new User();
            $user->setId($post_list['userId']);
            $user->setUsername($post_list['username']);
            $post=new Post();
            $post->setId($post_list['postId']);
            $post->setContent($post_list['content']);
            $post->setAuthor($user);
            $post->setPostedAt($post_list['posteAt']);
            $post_list[]=$post;
        }
       return $post;
    }
}