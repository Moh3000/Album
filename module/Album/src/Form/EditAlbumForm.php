<?php

namespace Album\Form;

use Laminas\Form\Form;
use Laminas\Stdlib\InitializableInterface;

class EditAlbumForm extends Form implements InitializableInterface
{
    public function init(): void
    {
        // SAME fieldset - reused!
        $this->add([
            'type'    => AlbumFieldset::class,
            'name'    => 'album',
            'options' => ['use_as_base_fieldset' => true],
        ]);

        $this->add([
            'type'       => 'submit',
            'name'       => 'submit',
            'attributes' => [
                'value' => 'Save Changes',
                'class' => 'btn btn-success',
            ],
        ]);
    }
}
