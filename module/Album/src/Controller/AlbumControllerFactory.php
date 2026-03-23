<?php

namespace Album\Controller;

use Album\Form\AlbumForm;
use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class AlbumControllerFactory implements FactoryInterface
{
    public function __invoke(
        ContainerInterface $container,
        $requestedName,
        ?array $options = null
    ): AlbumController {
        $entityManager = $container->get(EntityManager::class);
        $albumForm     = $container->get(AlbumForm::class);

        return new AlbumController($entityManager, $albumForm);
    }
}
