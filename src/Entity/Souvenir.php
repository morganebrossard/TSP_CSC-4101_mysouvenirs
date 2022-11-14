<?php

namespace App\Entity;

use App\Repository\SouvenirRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ORM\Entity(repositoryClass=SouvenirRepository::class)
 * @Vich\Uploadable
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





/**
 * @ORM\Column(type="string", length=255, nullable=true)
 * @var string
 */
private $imageName;

/**
 * @Vich\UploadableField(mapping="souvenirs", fileNameProperty="imageName")
 * @var File
 */
private $imageFile;

/**
 * @ORM\Column(type="datetime", nullable=true)
 *
 * @var \DateTime
 */
private $imageUpdatedAt;

//...
/**
 * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
 * of 'UploadedFile' is injected into this setter to trigger the update. If this
 * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
 * must be able to accept an instance of 'File' as the bundle will inject one here
 * during Doctrine hydration.
 *
 * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $imageFile
 */
public function setImageFile(?File $imageFile = null): void
{
    $this->imageFile = $imageFile;

    if (null !== $imageFile) {
    // It is required that at least one field changes if you are using doctrine
    // otherwise the event listeners won't be called and the file is lost
    $this->imageUpdatedAt = new \DateTimeImmutable();
    }
}

public function getImageFile(): ?File
{
    return $this->imageFile;
}

public function setImageName(?string $imageName): void
{
    $this->imageName = $imageName;
}

public function getImageName(): ?string
{
    return $this->imageName;
}



}