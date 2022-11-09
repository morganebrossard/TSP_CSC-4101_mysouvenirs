<?php

namespace App\Entity;

use App\Repository\ContextRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContextRepository::class)
 */
class Context
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
    private $label;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Context::class, inversedBy="subcontexts")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity=Context::class, mappedBy="parent")
     */
    private $subcontexts;

    /**
     * @ORM\ManyToMany(targetEntity=Souvenir::class, mappedBy="contexts")
     */
    private $souvenirs;

    public function __construct()
    {
        $this->subcontexts = new ArrayCollection();
        $this->souvenirs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getSubcontexts(): Collection
    {
        return $this->subcontexts;
    }

    public function addSubcontext(self $subcontext): self
    {
        if (!$this->subcontexts->contains($subcontext)) {
            $this->subcontexts[] = $subcontext;
            $subcontext->setParent($this);
        }

        return $this;
    }

    public function removeSubcontext(self $subcontext): self
    {
        if ($this->subcontexts->removeElement($subcontext)) {
            // set the owning side to null (unless already changed)
            if ($subcontext->getParent() === $this) {
                $subcontext->setParent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Souvenir>
     */
    public function getSouvenirs(): Collection
    {
        return $this->souvenirs;
    }

    public function addSouvenir(Souvenir $souvenir): self
    {
        if (!$this->souvenirs->contains($souvenir)) {
            $this->souvenirs[] = $souvenir;
            $souvenir->addContext($this);
        }

        return $this;
    }

    public function removeSouvenir(Souvenir $souvenir): self
    {
        if ($this->souvenirs->removeElement($souvenir)) {
            $souvenir->removeContext($this);
        }

        return $this;
    }
}
