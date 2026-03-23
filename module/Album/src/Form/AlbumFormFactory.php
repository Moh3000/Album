<?php

namespace Album\Form;

use Doctrine\ORM\EntityManager;

use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class AlbumFormFactory implements FactoryInterface
{
    public function __invoke(
        ContainerInterface $container,
        $requestedName,
        ?array $options = null
    ): AlbumForm {
        $entityManager = $container->get(EntityManager::class);

        $form = new AlbumForm($entityManager);

        // If this factory is invoked as part of FormElementManager, configure the form factory with it,
        // so nested fieldsets/elements are resolved through the same container.
        if ($container instanceof \Laminas\Form\FormElementManager) {
            $form->getFormFactory()->setFormElementManager($container);
        } elseif ($container->has(\Laminas\Form\FormElementManager::class)) {
            $form->getFormFactory()->setFormElementManager($container->get(\Laminas\Form\FormElementManager::class));
        }

        $form->init();

        return $form;
    }
}
