<?php

namespace App\Entity;

use App\Repository\JokeCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: JokeCategoryRepository::class)]
class JokeCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read-joke', 'read-jokes'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['read-joke', 'read-jokes'])]
    private ?string $name = null;

    /**
     * @var Collection<int, Joke>
     */
    #[ORM\OneToMany(targetEntity: Joke::class, mappedBy: 'jokeCategory')]
    private Collection $jokes;

    public function __construct()
    {
        $this->jokes = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Joke>
     */
    public function getJokes(): Collection
    {
        return $this->jokes;
    }

    public function addJoke(Joke $joke): static
    {
        if (!$this->jokes->contains($joke)) {
            $this->jokes->add($joke);
            $joke->setJokeCategory($this);
        }

        return $this;
    }

    public function removeJoke(Joke $joke): static
    {
        if ($this->jokes->removeElement($joke)) {
            // set the owning side to null (unless already changed)
            if ($joke->getJokeCategory() === $this) {
                $joke->setJokeCategory(null);
            }
        }

        return $this;
    }
}
