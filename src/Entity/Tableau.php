<?php

namespace App\Entity;

use App\Repository\TableauRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TableauRepository::class)
 */
class Tableau
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $publie;

    /**
     * @ORM\ManyToOne(targetEntity=Member::class, inversedBy="tableaux")
     * @ORM\JoinColumn(nullable=false)
     */
    private $createur;

    /**
     * @ORM\ManyToMany(targetEntity=Souvenir::class, inversedBy="tableaux")
     */
    private $souvenir;

    public function __construct()
    {
        $this->souvenir = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function isPublie(): ?bool
    {
        return $this->publie;
    }

    public function setPublie(bool $publie): self
    {
        $this->publie = $publie;

        return $this;
    }

    public function getCreateur(): ?Member
    {
        return $this->createur;
    }

    public function setCreateur(?Member $createur): self
    {
        $this->createur = $createur;

        return $this;
    }

    /**
     * @return Collection<int, Souvenir>
     */
    public function getSouvenir(): Collection
    {
        return $this->souvenir;
    }

    public function addSouvenir(Souvenir $souvenir): self
    {
        if (!$this->souvenir->contains($souvenir)) {
            $this->souvenir[] = $souvenir;
        }

        return $this;
    }

    public function removeSouvenir(Souvenir $souvenir): self
    {
        $this->souvenir->removeElement($souvenir);

        return $this;
    }
}
