<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
    public function all(#[MapQueryParameter] int $page = 1) {

        return $this->json(
            $this->repo->findAll(($page-1)*15, 15)
        );
    }

    #[Route('api/user/{username}',methods:'GET')]
    public function byAuthor(
        string $username, 
        UserRepository $userRepo, 
        #[MapQueryParameter] int $page = 1) {
        //Pourquoi pas vérifier si le user existe avant de récupérer ses posts, pour renvoyer un 404 si jamais c'est pas le cas
        if(!$userRepo->findByIdentifier($username)) {
            throw new NotFoundHttpException('User does not exists');
        }

        return $this->json(
            $this->repo->postByusername($username,($page-1)*15, 15)
        );
    }
}   