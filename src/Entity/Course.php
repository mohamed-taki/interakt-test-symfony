<?php

namespace App\Entity;

use App\Repository\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CourseRepository::class)]
class Course
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::FLOAT)]
    private ?float $price = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'favBy')]
    private Collection $favBy;

    #[ORM\ManyToOne(inversedBy: 'createdCourses')]
    #[ORM\JoinColumn(nullable: false, onDelete:"CASCADE")]
    private ?User $creator = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $imagePath = null;

    public function __construct()
    {
        $this->favBy = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }


    public function setTitle(string $title): static {
        $this->title = $title;
        return $this;
    }



    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getFavCourses(): Collection
    {
        return $this->favBy;
    }

    public function addFavCourse(User $favCourse): static
    {
        if (!$this->favBy->contains($favCourse)) {
            $this->favBy->add($favCourse);
            $favCourse->addFavCourse($this);
        }

        return $this;
    }

    public function removeFavCourse(User $favCourse): static
    {
        if ($this->favBy->removeElement($favCourse)) {
            $favCourse->removeFavCourse($this);
        }

        return $this;
    }

    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setCreator(?User $creator): static
    {
        $this->creator = $creator;

        return $this;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(string $imagePath): static
    {
        $this->imagePath = $imagePath;

        return $this;
    }
}
