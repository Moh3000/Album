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
                'required'    => true,
                
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
