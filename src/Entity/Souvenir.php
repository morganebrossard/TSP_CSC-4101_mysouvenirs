<?php

namespace App\Entity;

use App\Repository\SouvenirRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SouvenirRepository::class)
 */
class Souvenir
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
    private $title;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $place;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Album::class, inversedBy="souvenir")
     */
    private $album;

    /**
     * @ORM\ManyToMany(targetEntity=Tableau::class, mappedBy="souvenir")
     */
    private $tableaux;

    /**
     * @ORM\ManyToMany(targetEntity=Context::class, inversedBy="souvenirs")
     */
    private $contexts;

    public function __construct()
    {
        $this->tableaux = new ArrayCollection();
        $this->contexts = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(string $place): self
    {
        $this->place = $place;

        return $this;
    }


    public function getContext(): ?string
    {
        return $this->context;
    }

    public function setContext(string $context): self
    {
        $this->context = $context;

        return $this;
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

    public function getAlbum(): ?Album
    {
        return $this->album;
    }

    public function setAlbum(?Album $album): self
    {
        $this->album = $album;

        return $this;
    }

    public function __toString() {
        return $this->title;
    }

    /**
     * @return Collection<int, Tableau>
     */
    public function getTableaux(): Collection
    {
        return $this->tableaux;
    }

    public function addTableaux(Tableau $tableaux): self
    {
        if (!$this->tableaux->contains($tableaux)) {
            $this->tableaux[] = $tableaux;
            $tableaux->addSouvenir($this);
        }

        return $this;
    }

    public function removeTableaux(Tableau $tableaux): self
    {
        if ($this->tableaux->removeElement($tableaux)) {
            $tableaux->removeSouvenir($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Context>
     */
    public function getContexts(): Collection
    {
        return $this->contexts;
    }

    public function addContext(Context $context): self
    {
        if (!$this->contexts->contains($context)) {
            $this->contexts[] = $context;
        }

        return $this;
    }

    public function removeContext(Context $context): self
    {
        $this->contexts->removeElement($context);

        return $this;
    }

}