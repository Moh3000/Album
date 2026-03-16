<?php

namespace Album\Form;

use Laminas\Form\Form;

class AddAlbumForm extends Form
{
    public function init(): void
    {
        $this->add([
            'type'    => AlbumFieldset::class,
            'name'    => 'album',
            'options' => ['use_as_base_fieldset' => true],
        ]);

        $this->add([
            'type'       => 'submit',
            'name'       => 'submit',
            'attributes' => [
                'value' => 'Add Album',
                'class' => 'btn btn-primary',
            ],
        ]);
    }
}
