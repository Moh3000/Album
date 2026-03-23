<?php

namespace Album\Form;

use Doctrine\ORM\EntityManager;
use Laminas\Form\Form;

class AlbumForm extends Form
{
    private EntityManager $entityManager;
    private AuthorFieldset $authorFieldset;

    public function __construct(
        EntityManager $entityManager,
        AuthorFieldset $authorFieldset
    ) {
        parent::__construct('album-form');
        $this->entityManager  = $entityManager;
        $this->authorFieldset = $authorFieldset;
    }

    public function init(): void
    {
        $this->add([
            'type' => 'hidden',
            'name' => 'id',
        ]);

        $this->add([
            'type'       => 'text',
            'name'       => 'title',
            'options'    => ['label' => 'Title'],
            'attributes' => [
                'class'       => 'form-control',
                'placeholder' => 'Enter album title',
            ],
        ]);

        $this->add([
            'type'       => 'text',
            'name'       => 'artist',
            'options'    => ['label' => 'Artist'],
            'attributes' => [
                'class'       => 'form-control',
                'placeholder' => 'Enter artist name',
            ],
        ]);

        // use the injected AuthorFieldset instance
        $this->add([
            'type'    => 'collection',
            'name'    => 'authors',
            'options' => [
                'label'                  => 'Authors',
                'count'                  => 3,
                'should_create_template' => true,
                'allow_add'              => true,
                'allow_remove'           => true,
                'target_element'         => $this->authorFieldset,
            ],
        ]);

        $this->add([
            'type'       => 'submit',
            'name'       => 'submit',
            'attributes' => [
                'value' => 'Save',
                'class' => 'btn btn-primary',
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
                    [
                        'name'    => 'StringLength',
                        'options' => ['min' => 1, 'max' => 100],
                    ],
                ],
            ],
            'artist' => [
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
