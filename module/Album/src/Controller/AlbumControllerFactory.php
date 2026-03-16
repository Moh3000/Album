<?php

namespace Album\Controller;

use Doctrine\ORM\EntityManager;
use Laminas\Form\FormElementManager;
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
        $formManager   = $container->get(FormElementManager::class);

        return new AlbumController($entityManager, $formManager);
    }
}