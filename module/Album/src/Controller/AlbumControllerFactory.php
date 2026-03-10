<?php

namespace Album\Controller;

use Doctrine\ORM\EntityManager;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class AlbumControllerFactory implements FactoryInterface
{
    public function __invoke(
        ContainerInterface $container,
        $requestedName,
        ?array $options = null
    ) {
        $entityManager = $container->get(EntityManager::class);
        return new AlbumController($entityManager);
    }
}
