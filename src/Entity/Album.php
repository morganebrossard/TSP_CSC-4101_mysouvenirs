<?php

namespace App\Entity;

use App\Repository\AlbumRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AlbumRepository::class)
 */
class Album
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=Souvenir::class, mappedBy="album", cascade={"persist"})
     */
    private $souvenir;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Member::class, inversedBy="album")
     * @ORM\JoinColumn(nullable=false)
     */
    private $member;

    public function __construct()
    {
        $this->souvenir = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $souvenir->setAlbum($this);
        }

        return $this;
    }

    public function removeSouvenir(Souvenir $souvenir): self
    {
        if ($this->souvenir->removeElement($souvenir)) {
            // set the owning side to null (unless already changed)
            if ($souvenir->getAlbum() === $this) {
                $souvenir->setAlbum(null);
            }
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getMember(): ?Member
    {
        return $this->member;
    }

    public function setMember(?Member $member): self
    {
        $this->member = $member;

        return $this;
    }

    #Ajout d'une fonction ToString pour représenter un album (ici son nom)
    public function __toString() {
        return $this->name;
    }
}
