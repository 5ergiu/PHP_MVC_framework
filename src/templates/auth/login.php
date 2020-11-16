<?php

$this->element('test');

echo $this->form->create(null);

echo $this->form->input('email', [
    'type' => 'email',
    'label' => [
        'class' => 'test',
        'text' => 'email',
    ],
]);

echo $this->form->input('name', [
    'label' => [
        'class' => 'test',
        'text' => 'name',
    ],
    'multiple' => true,
]);

echo $this->form->button('Save', [
    'class' => 'button'
]);
echo $this->form->end();
