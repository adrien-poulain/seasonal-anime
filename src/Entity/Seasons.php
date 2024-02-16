<?php

namespace App\Entity;

use App\Repository\SeasonsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SeasonsRepository::class)]
class Seasons
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $start = null;

    #[ORM\OneToMany(targetEntity: Animes::class, mappedBy: 'season')]
    private Collection $animes_list;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updated_at = null;

    public function __construct()
    {
        $this->animes_list = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(\DateTimeInterface $start): static
    {
        $this->start = $start;

        return $this;
    }

    /**
     * @return Collection<int, Animes>
     */
    public function getAnimesList(): Collection
    {
        return $this->animes_list;
    }

    public function addAnimesList(Animes $animesList): static
    {
        if (!$this->animes_list->contains($animesList)) {
            $this->animes_list->add($animesList);
            $animesList->setSeasonId($this);
        }

        return $this;
    }

    public function removeAnimesList(Animes $animesList): static
    {
        if ($this->animes_list->removeElement($animesList)) {
            // set the owning side to null (unless already changed)
            if ($animesList->getSeasonId() === $this) {
                $animesList->setSeasonId(null);
            }
        }

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}
