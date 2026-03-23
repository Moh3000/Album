<?php

namespace Album;

use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'album' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/album[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\AlbumController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            Controller\AlbumController::class => Controller\AlbumControllerFactory::class,
        ],
    ],

    'form_elements' => [
        'factories' => [
            Album\Form\AddAlbumForm::class => InvokableFactory::class,
            Album\Form\EditAlbumForm::class => InvokableFactory::class,
            Album\Form\AlbumFieldset::class => InvokableFactory::class,
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],

    'doctrine' => [
        'driver' => [
            'Album_driver' => [
                'class' => \Doctrine\ORM\Mapping\Driver\AttributeDriver::class,
                'paths' => [__DIR__ . '/../src/Entity'],
            ],
            'orm_default' => [
                'drivers' => [
                    'Album\Entity' => 'Album_driver',
                ],
            ],
        ],
    ],
];
