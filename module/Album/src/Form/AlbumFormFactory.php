<?php

namespace Album\Form;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;

class AlbumFormFactory
{
    public function __invoke(ContainerInterface $container): AlbumForm
    {
        $entityManager  = $container->get(EntityManager::class);

        // create AuthorFieldset manually with EntityManager
        $authorFieldset = new AuthorFieldset($entityManager);

        $form = new AlbumForm($entityManager, $authorFieldset);
        $form->init();

        return $form;
    }
}
