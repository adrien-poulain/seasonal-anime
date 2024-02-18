<?php

namespace App\Entity;

use App\Repository\AnimesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnimesRepository::class)]
class Animes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $anime_id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $thumbnail_link = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $summary = null;

    #[ORM\Column(length: 100)]
    private ?string $publish_day = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $publish_time = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $publish_start = null;

    #[ORM\Column]
    private ?int $nb_episodes = null;

    #[ORM\ManyToOne(inversedBy: 'animes_list')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Seasons $season = null;

    public function getAnimeId(): ?int
    {
        return $this->anime_id;
    }

    public function getThumbnailLink(): ?string
    {
        return $this->thumbnail_link;
    }

    public function setThumbnailLink(string $thumbnail_link): static
    {
        $this->thumbnail_link = $thumbnail_link;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): static
    {
        $this->summary = $summary;

        return $this;
    }

    public function getPublishDay(): ?string
    {
        return $this->publish_day;
    }

    public function setPublishDay(string $publish_day): static
    {
        $this->publish_day = $publish_day;

        return $this;
    }

    public function getPublishTime(): ?\DateTimeInterface
    {
        return $this->publish_time;
    }

    public function setPublishTime(\DateTimeInterface $publish_time): static
    {
        $this->publish_time = $publish_time;

        return $this;
    }

    public function getPublishStart(): ?\DateTimeInterface
    {
        return $this->publish_start;
    }

    public function setPublishStart(\DateTimeInterface $publish_start): static
    {
        $this->publish_start = $publish_start;

        return $this;
    }

    public function getNbEpisodes(): ?int
    {
        return $this->nb_episodes;
    }

    public function setNbEpisodes(int $nb_episodes): static
    {
        $this->nb_episodes = $nb_episodes;

        return $this;
    }

    public function getSeason(): ?Seasons
    {
        return $this->season;
    }

    public function setSeasonId(?Seasons $season): static
    {
        $this->season = $season;

        return $this;
    }
}
