<?php

namespace Album\Form;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;

class AuthorFieldsetFactory
{
    public function __invoke(ContainerInterface $container): AuthorFieldset
    {
        $entityManager = $container->get(EntityManager::class);
        return new AuthorFieldset($entityManager);
    }
}
