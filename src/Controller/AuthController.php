<?php

namespace App\Controller;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;
use function Symfony\Component\Clock\now;


class AuthController extends AbstractController{

    public function __construct(private UserRepository $repo){}

    #[Route('/api/user',methods: 'POST')]
    public function register(
        #[MapRequestPayload] User $user,
        UserPasswordHasherInterface $hasher
    ){
        if($this->repo->findByIdentifier($user->getUsername())!=null){
            return $this->json('Username already exists',400);
        }
        if($this->repo->findByIdentifier($user->getEmail())!=null){
            return $this->json('Email already exists',400);
        }
        $hashedPassword = $hasher->hashPassword($user,$user->getPassword());
        $user->setPassword($hashedPassword);
        $user->setCreateAt(new \DateTimeImmutable());
        $user->setRole('ROLE_USER');
        $this->repo->persist($user);
        return $this->json($user, 201);
    }
    #[Route('/api/user', methods:'GET')]
    public function getConnectedUser() {
        return $this->json($this->getUser());
    }
}