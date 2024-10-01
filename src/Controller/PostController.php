<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class PostController extends AbstractController{
    public function __construct(private PostRepository $repo){}

    #[Route("/api/post", methods:"POST")]
    public function addPost(#[MapRequestPayload] Post $post){
        $post->setPostedAt(new \DateTimeImmutable());
        $post->setAuthor($this->getUser());
        $this->repo->persistPost($post);
        return $this->json($post, 201);
    }
    #[Route("/api/post", methods:"GET")]
    public function getAllPostst(){
        $page=1;
        return $this->json($this->repo->findAll(offset:($page-1)*15,limit:15));
    }
}   