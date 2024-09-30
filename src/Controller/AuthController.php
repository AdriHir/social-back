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
        if($this->repo->findByUsername($user->getUsername())!=null){
            return $this->json('User already exists',400);
        }
        $hashedPassword = $hasher->hashPassword($user,$user->getPassword());
        $user->setPassword($hashedPassword);
        $user->setCreateAt(new \DateTimeImmutable());
        $user->setRole('ROLE_USER');
        $this->repo->persist($user);
        return $this->json($user, 201);
    }
}