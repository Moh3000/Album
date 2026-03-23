<?php

namespace Album\Form;

use Doctrine\Laminas\Hydrator\DoctrineObject as DoctrineHydrator;
use Album\Entity\Author;
use Laminas\Form\Fieldset;
use Doctrine\ORM\EntityManager;
use Laminas\InputFilter\InputFilterProviderInterface;

class AuthorFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function __construct(EntityManager $entityManager)
    {
        parent::__construct('author');

        $this->setHydrator(new DoctrineHydrator($entityManager, Author::class));
    }

    public function init(): void
    {
        $this->add([
            'type' => 'hidden',
            'name' => 'id',
        ]);

        $this->add([
            'type'       => 'text',
            'name'       => 'name',
            'options'    => ['label' => 'Author Name'],
            'attributes' => [
                'class'       => 'form-control',
                'placeholder' => 'Enter author name',
            ],
        ]);
    }

    public function getInputFilterSpecification(): array
    {
        return [
            'id' => [
                'required' => false,
            ],
            'name' => [
                'required' => true,
                'filters'  => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ],
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => ['min' => 1, 'max' => 100],
                    ],
                ],
            ],
        ];
    }
}
