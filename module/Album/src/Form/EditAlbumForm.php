<?php

namespace Album\Form;

use Laminas\Form\Form;

class EditAlbumForm extends Form
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
