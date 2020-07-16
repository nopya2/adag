<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EvenementRepository")
 *
 * @ExclusionPolicy("all")
 */
class Evenement
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Expose
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Expose
     */
    private $titre;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotNull(
     *  message = "Veuillez saisir une description SVP."
     * )
     * @Expose
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="evenements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="text")
     * @Expose
     */
    private $place;

    /**
     * @ORM\Column(type="datetime")
     * @Expose
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Expose
     */
    private $dateFin;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Image", cascade={"persist"})
     */
    private $images;

    public function __construct(){
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->dateDebut = new \DateTime();
        $this->dateFin = new \DateTime();
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

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

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

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

    public function getDateDebut()
    {
        if(is_null($this->dateDebut))
            return $this->dateDebut;
        
        $day = date_format($this->dateDebut, 'd');
        $month = date_format($this->dateDebut, 'm');
        $year = date_format($this->dateDebut, 'Y');
        $hour = date_format($this->dateDebut, 'H');
        $minute = date_format($this->dateDebut, 'i');
        return $year.'-'.$month.'-'.$day.' '.$hour.':'.$minute;
    }

    public function setDateDebut($dateDebut): self
    {
        //$this->dateDebut = $dateDebut;
        $this->dateDebut = new \DateTime($dateDebut);

        return $this;
    }

    public function getDateFin()
    {
        if(is_null($this->dateFin))
            return $this->dateFin;
            
        $day = date_format($this->dateFin, 'd');
        $month = date_format($this->dateFin, 'm');
        $year = date_format($this->dateFin, 'Y');
        $hour = date_format($this->dateFin, 'H');
        $minute = date_format($this->dateFin, 'i');
        return $year.'-'.$month.'-'.$day.' '.$hour.':'.$minute;
    }

    public function setDateFin($dateFin): self
    {
        //$this->dateFin = $dateFin;
        $this->dateFin = new \DateTime($dateFin);

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
        }

        return $this;
    }
}
