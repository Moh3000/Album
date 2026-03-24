<?php

namespace Album\Entity;

use Album\Repository\AlbumRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AlbumRepository::class)]
#[ORM\Table(name: 'album')]
class Album
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 100)]
    private string $title = '';

    #[ORM\Column(type: 'string', length: 100)]
    private string $artist = '';

    
    #[ORM\OneToMany(
        targetEntity: Author::class,
        mappedBy: 'album',
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $authors;

    public function __construct()
    {
        $this->authors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getArtist(): string
    {
        return $this->artist;
    }

    public function setArtist(string $artist): void
    {
        $this->artist = $artist;
    }

    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    // helper method to ADD author
    public function addAuthor(Author $author): void
    {
        if (!$this->authors->contains($author)) {
            $this->authors->add($author);
            $author->setAlbum($this); // sync owning side!
        }
    }

    // helper method to REMOVE author
    public function removeAuthor(Author $author): void
    {
        if ($this->authors->contains($author)) {
            $this->authors->removeElement($author);
            $author->setAlbum(null); // sync owning side!
        }
    }
}
