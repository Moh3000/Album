<?php

namespace Album\Form;

use Album\Entity\Album;
use Laminas\Form\Fieldset;
use Laminas\Hydrator\ClassMethodsHydrator;
use Laminas\InputFilter\InputFilterProviderInterface;

class AlbumFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function __construct()
    {
        parent::__construct('album');

        $this->setHydrator(new ClassMethodsHydrator());
        $this->setObject(new Album());
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
            'attributes' => ['class' => 'form-control'],
        ]);

        $this->add([
            'type'       => 'text',
            'name'       => 'artist',
            'options'    => ['label' => 'Artist'],
            'attributes' => ['class' => 'form-control'],
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
