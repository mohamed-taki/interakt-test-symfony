<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column]
    private bool $isVerified = false;

    #[ORM\Column(length: 255)]
    private ?string $fname = null;

    #[ORM\Column(length: 255)]
    private ?string $lname = null;

    #[ORM\Column(length: 10)]
    private ?int $phone = null;

    #[ORM\Column(length: 4)]
    private ?string $phone_ext = null;

    /**
     * @var Collection<int, Course>
     */
    #[ORM\ManyToMany(targetEntity: Course::class, inversedBy: 'favBy')]
    #[ORM\JoinColumn(nullable:true)]
    private Collection $favCourses;

    /**
     * @var Collection<int, Course>
     */
    #[ORM\OneToMany(targetEntity: Course::class, mappedBy: 'creator', orphanRemoval: true)]
    private Collection $createdCourses;

    public function __construct()
    {
        $this->favCourses = new ArrayCollection();
        $this->createdCourses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getFname(): ?string
    {
        return $this->fname;
    }

    public function setFname(string $fname): static
    {
        $this->fname = $fname;

        return $this;
    }

    public function getLname(): ?string
    {
        return $this->lname;
    }

    public function setLname(string $lname): static
    {
        $this->lname = $lname;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(int $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getPhoneExt(): ?string
    {
        return $this->phone_ext;
    }

    public function setPhoneExt(string $phone_ext): static
    {
        $this->phone_ext = $phone_ext;

        return $this;
    }

    /**
     * @return Collection<int, Course>
     */
    public function getFavCourses(): Collection
    {
        return $this->favCourses;
    }

    public function addFavCourse(Course $favCourse): static
    {
        if (!$this->favCourses->contains($favCourse)) {
            $this->favCourses->add($favCourse);
        }

        return $this;
    }

    public function removeFavCourse(Course $favCourse): static
    {
        $this->favCourses->removeElement($favCourse);

        return $this;
    }

    /**
     * @return Collection<int, Course>
     */
    public function getCreatedCourses(): Collection
    {
        return $this->createdCourses;
    }

    public function addCreatedCourse(Course $createdCourse): static
    {
        if (!$this->createdCourses->contains($createdCourse)) {
            $this->createdCourses->add($createdCourse);
            $createdCourse->setCreator($this);
        }

        return $this;
    }

    public function removeCreatedCourse(Course $createdCourse): static
    {
        if ($this->createdCourses->removeElement($createdCourse)) {
            // set the owning side to null (unless already changed)
            if ($createdCourse->getCreator() === $this) {
                $createdCourse->setCreator(null);
            }
        }

        return $this;
    }
}
