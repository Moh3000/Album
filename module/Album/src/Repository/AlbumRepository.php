<?php

namespace Album\Repository;

use Album\Entity\Album;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;

class AlbumRepository extends EntityRepository
{
    public function findAllAlbums(): array
    {
        return $this->findAll();
    }

    public function findPaginated(int $page, int $perPage = 3): DoctrinePaginator
    {
        $query = $this->createQueryBuilder('a')
            ->orderBy('a.id', 'ASC')
            ->getQuery();

        $query->setFirstResult(($page - 1) * $perPage)
            ->setMaxResults($perPage);

        return new DoctrinePaginator($query);
    }
}
