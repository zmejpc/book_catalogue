<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'author')]
#[ORM\Entity(repositoryClass: 'App\Repository\AuthorRepository')]
#
class Author
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(name: 'id', type: 'integer', nullable: false, options: ['unsigned' => true])]
    #
    private ?int $id = null;

    #[Assert\NotBlank()]
    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    #
    private ?string $name = null;

    #[Assert\NotBlank()]
    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    #
    private ?string $surname = null;

    #[Assert\NotBlank()]
    #[Assert\Length(min: 3)]
    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    #
    private ?string $lastname = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): static
    {
        $this->surname = $surname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getDisplayName(): string
    {
        return trim(implode(' ', [
            $this->lastname,
            $this->name,
            $this->surname,
        ]));
    }
}