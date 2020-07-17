<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * HasLifecycleCallbacks + méthode prePersist =>
 * Permet de modifier l’entité pour enregister la date de création du produit à la date ou on valide le produit
 * @UniqueEntity(fields={"email"}, message="Cet e-mail est déjà utilisé par quelqu'un.")
 * @UniqueEntity(fields={"pseudo"}, message="Ce pseudo est déjà utilisé par quelqu'un.")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $pseudo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $telephone;

    /**
     * @ORM\Column(type="date")
     */
    private $inscription;

    /**
     * @ORM\OneToMany(targetEntity=Annonce::class, mappedBy="auteur")
     */
    private $annonces;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="auteur")
     */
    private $commentaires;

    /**
     * @ORM\OneToMany(targetEntity=Note::class, mappedBy="auteur")
     */
    private $notes;

    /**
     * @ORM\OneToMany(targetEntity=Note::class, mappedBy="user")
     */
    private $notes_utilisateur;

    public function __construct()
    {
        $this->annonces = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->notes = new ArrayCollection();
        $this->notes_utilisateur = new ArrayCollection();
    }

    /**
     * Méthode exécutée avant l'insertion en base
     * @ORM\PrePersist()
     * Modifier l’entité pour enregister la date de création du produit à la date ou on valide le produit
     */
    public function prePersist()
    {
        if ($this->inscription === null) {
            $this->inscription = new \DateTime();
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getInscription(): ?\DateTimeInterface
    {
        return $this->inscription;
    }

    public function setInscription(\DateTimeInterface $inscription): self
    {
        $this->inscription = $inscription;

        return $this;
    }

    /**
     * @return Collection|Annonce[]
     */
    public function getAnnonces(): Collection
    {
        return $this->annonces;
    }

    public function addAnnonce(Annonce $annonce): self
    {
        if (!$this->annonces->contains($annonce)) {
            $this->annonces[] = $annonce;
            $annonce->setAuteur($this);
        }

        return $this;
    }

    public function removeAnnonce(Annonce $annonce): self
    {
        if ($this->annonces->contains($annonce)) {
            $this->annonces->removeElement($annonce);
            // set the owning side to null (unless already changed)
            if ($annonce->getAuteur() === $this) {
                $annonce->setAuteur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Commentaire[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setAuteur($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->contains($commentaire)) {
            $this->commentaires->removeElement($commentaire);
            // set the owning side to null (unless already changed)
            if ($commentaire->getAuteur() === $this) {
                $commentaire->setAuteur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Note[]
     * les notes ou il est auteur
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Note $note): self
    {
        if (!$this->notes->contains($note)) {
            $this->notes[] = $note;
            $note->setAuteur($this);
        }

        return $this;
    }

    public function removeNote(Note $note): self
    {
        if ($this->notes->contains($note)) {
            $this->notes->removeElement($note);
            // set the owning side to null (unless already changed)
            if ($note->getAuteur() === $this) {
                $note->setAuteur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Note[]
     * les notes ou il est user
     */
    public function getNotesUtilisateur(): Collection
    {
        return $this->notes_utilisateur;
    }

    public function addNotesUtilisateur(Note $notesUtilisateur): self
    {
        if (!$this->notes_utilisateur->contains($notesUtilisateur)) {
            $this->notes_utilisateur[] = $notesUtilisateur;
            $notesUtilisateur->setUser($this);
        }

        return $this;
    }

    public function removeNotesUtilisateur(Note $notesUtilisateur): self
    {
        if ($this->notes_utilisateur->contains($notesUtilisateur)) {
            $this->notes_utilisateur->removeElement($notesUtilisateur);
            // set the owning side to null (unless already changed)
            if ($notesUtilisateur->getUser() === $this) {
                $notesUtilisateur->setUser(null);
            }
        }

        return $this;
    }
}
