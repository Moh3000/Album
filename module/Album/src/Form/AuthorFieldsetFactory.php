<?php

namespace Album\Form;

use Album\Form\AuthorFieldset;
use Doctrine\ORM\EntityManager;

use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class AuthorFieldsetFactory implements FactoryInterface
{
    public function __invoke(
        ContainerInterface $container,
        $requestedName,
        ?array $options = null
    ): AuthorFieldset {
        $entityManager = $container->get(EntityManager::class);
        

        return new AuthorFieldset($entityManager);
    }
}
