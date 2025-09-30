<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read-chose'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['read-chose'])]
    private ?string $name = null;

    /**
     * @var Collection<int, Chose>
     */
    #[ORM\OneToMany(targetEntity: Chose::class, mappedBy: 'category')]
    private Collection $choses;

    public function __construct()
    {
        $this->choses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Chose>
     */
    public function getChoses(): Collection
    {
        return $this->choses;
    }

    public function addChose(Chose $chose): static
    {
        if (!$this->choses->contains($chose)) {
            $this->choses->add($chose);
            $chose->setCategory($this);
        }

        return $this;
    }

    public function removeChose(Chose $chose): static
    {
        if ($this->choses->removeElement($chose)) {
            // set the owning side to null (unless already changed)
            if ($chose->getCategory() === $this) {
                $chose->setCategory(null);
            }
        }

        return $this;
    }
}
