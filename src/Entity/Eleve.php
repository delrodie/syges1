<?php

namespace App\Entity;

use App\Repository\EleveRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EleveRepository::class)
 */
class Eleve
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $matricule;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenoms;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $dateNaissance;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lieuNaissance;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sexe;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nationalite;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $domicile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nomParent;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $professionParent;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $contactParent;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $residence;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nomTuteur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $professionTuteur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $contactTuteur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $residenceTuteur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $annee;

    /**
     * @ORM\ManyToOne(targetEntity=Classe::class, inversedBy="eleves")
     */
    private $classe;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(?string $matricule): self
    {
        $this->matricule = $matricule;

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

    public function getPrenoms(): ?string
    {
        return $this->prenoms;
    }

    public function setPrenoms(string $prenoms): self
    {
        $this->prenoms = $prenoms;

        return $this;
    }

    public function getDateNaissance(): ?string
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(?string $dateNaissance): self
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getLieuNaissance(): ?string
    {
        return $this->lieuNaissance;
    }

    public function setLieuNaissance(?string $lieuNaissance): self
    {
        $this->lieuNaissance = $lieuNaissance;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getNationalite(): ?string
    {
        return $this->nationalite;
    }

    public function setNationalite(?string $nationalite): self
    {
        $this->nationalite = $nationalite;

        return $this;
    }

    public function getDomicile(): ?string
    {
        return $this->domicile;
    }

    public function setDomicile(?string $domicile): self
    {
        $this->domicile = $domicile;

        return $this;
    }

    public function getNomParent(): ?string
    {
        return $this->nomParent;
    }

    public function setNomParent(?string $nomParent): self
    {
        $this->nomParent = $nomParent;

        return $this;
    }

    public function getProfessionParent(): ?string
    {
        return $this->professionParent;
    }

    public function setProfessionParent(?string $professionParent): self
    {
        $this->professionParent = $professionParent;

        return $this;
    }

    public function getContactParent(): ?string
    {
        return $this->contactParent;
    }

    public function setContactParent(?string $contactParent): self
    {
        $this->contactParent = $contactParent;

        return $this;
    }

    public function getResidence(): ?string
    {
        return $this->residence;
    }

    public function setResidence(?string $residence): self
    {
        $this->residence = $residence;

        return $this;
    }

    public function getNomTuteur(): ?string
    {
        return $this->nomTuteur;
    }

    public function setNomTuteur(?string $nomTuteur): self
    {
        $this->nomTuteur = $nomTuteur;

        return $this;
    }

    public function getProfessionTuteur(): ?string
    {
        return $this->professionTuteur;
    }

    public function setProfessionTuteur(?string $professionTuteur): self
    {
        $this->professionTuteur = $professionTuteur;

        return $this;
    }

    public function getContactTuteur(): ?string
    {
        return $this->contactTuteur;
    }

    public function setContactTuteur(?string $contactTuteur): self
    {
        $this->contactTuteur = $contactTuteur;

        return $this;
    }

    public function getResidenceTuteur(): ?string
    {
        return $this->residenceTuteur;
    }

    public function setResidenceTuteur(?string $residenceTuteur): self
    {
        $this->residenceTuteur = $residenceTuteur;

        return $this;
    }

    public function getAnnee(): ?string
    {
        return $this->annee;
    }

    public function setAnnee(?string $annee): self
    {
        $this->annee = $annee;

        return $this;
    }

    public function getClasse(): ?Classe
    {
        return $this->classe;
    }

    public function setClasse(?Classe $classe): self
    {
        $this->classe = $classe;

        return $this;
    }
}
