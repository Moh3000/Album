<?php
namespace Album\Form;

use Album\Entity\Author;
use Doctrine\Laminas\Hydrator\DoctrineObject;
use Doctrine\ORM\EntityManager;
use Laminas\Form\Fieldset;
use Laminas\InputFilter\InputFilterProviderInterface;

class AuthorFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function __construct(EntityManager $entityManager)
    {
        parent::__construct('author');

        $this->setHydrator(new DoctrineObject($entityManager));
        $this->setObject(new Author());

        // add elements directly in constructor
        // because init() is only called by FormElementManager
        // we are NOT using FormElementManager here
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
            'name' => [
                'required' => false,
                'filters'  => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ],
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => ['min' => 0, 'max' => 100],
                    ],
                ],
            ],
        ];
    }
}
