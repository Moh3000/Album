<?php

namespace Album\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: \Album\Repository\AlbumRepository::class)]
#[ORM\Table(name: 'album')]
class Album
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 100)]
    private string $artist;

    #[ORM\Column(type: 'string', length: 100)]
    private string $title;
    

    public function getId()
    {
        return $this->id;
    }

    public function getArtist(): string
    {
        return $this->artist;
    }

    public function setArtist(string $artist): void
    {
        $this->artist = $artist;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
}
