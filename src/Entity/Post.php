<?php

namespace Src\Entity;
use App\Entity\User;
use Symfony\Component\Validator\Constraints\NotBlank;

class Post{
    private ?int $id = null;
    #[NotBlank]
    private ? string $content = null;
    private ?\DateTimeImmutable $postedAt= null;
    private ?User $user =null;
    private ?Post $respondTo = null ;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }
    public function getContent(): ?string
    {
        return $this->content;
    }
    public function setContent(?string $content): self
    {
        $this->content = $content;
        return $this;
    }
    
    public function getPostAt(): ?\DateTimeImmutable
    {
        return $this->postedAt;
    }
    public function setPostedAt(?\DateTimeImmutable $posteAt): self
    {
        $this->postedAt = $posteAt;
        return $this;
    }
    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
    public function getPost(): ?Post
    {
        return $this->respondTo;
    }

    public function setPost(?Post $respondTo): self
    {
        $this->respondTo = $respondTo;

        return $this;
    }

    

       

}