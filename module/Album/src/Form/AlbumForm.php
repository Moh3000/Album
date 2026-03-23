<?php

namespace Album\Form;
use Album\Entity\Album;
use Doctrine\Laminas\Hydrator\DoctrineObject as DoctrineHydrator;
use Doctrine\ORM\EntityManager;
use Laminas\Form\Form;
use Laminas\InputFilter\InputFilterProviderInterface;
use Doctrine\Common\Collections\Collection;

class AlbumForm extends Form  implements InputFilterProviderInterface
 
{

    public function __construct(EntityManager $entityManager)
    {
        parent::__construct('album');

        $this->setHydrator(new DoctrineHydrator($entityManager, Album::class));
    }
    public function init(): void
    {
       
        $this->add([
            'type'    => \Laminas\Form\Element\Collection::class,
            'name'    => 'Authors',
            'options' => [
                'label'          => 'Authors',
                'count'          => 3,
                'allow_add'      => true,
                'allow_remove'   => true,
                'target_element' => [
                    'type' => AuthorFieldset::class,
                ],
            ],
        ]);
        $this->add([
            'type' => 'hidden',
            'name' => 'id',
        ]);

        $this->add([
            'type'       => 'text',
            'name'       => 'title',
            'options'    => ['label' => 'Title'],
            'attributes' => ['class' => 'form-control'],
        ]);

        $this->add([
            'type'       => 'text',
            'name'       => 'artist',
            'options'    => ['label' => 'Artist'],
            'attributes' => ['class' => 'form-control'],
        ]);

        $this->add([
            'type'       => 'submit',
            'name'       => 'submit',
            'attributes' => [
                'value' => 'Save ',
                'class' => 'btn btn-success',
            ],
        ]);

    }

    public function getInputFilterSpecification(): array
    {
        return [
            'id'     => ['required' => false],
            'title'  => [
                'required' => true,
                'filters'  => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ],
                'validators' => [
                    ['name' => 'StringLength', 'options' => ['min' => 1, 'max' => 100]],
                ],
            ],
            'artist' => [
                'required' => true,
                'filters'  => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ],
                'validators' => [
                    ['name' => 'StringLength', 'options' => ['min' => 1, 'max' => 100]],
                ],
            ],
        ];
    }
}
