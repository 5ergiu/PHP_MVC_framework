<?php

$this->element('test');

$this->form->create(null);

$this->form->input('email', [
    'type' => 'email',
    'label' => [
        'class' => 'test',
        'text' => 'email',
    ],
]);

$this->form->input('name', [
    'type' => 'text',
    'label' => [
        'class' => 'test',
        'text' => 'name',
    ],
    'multiple' => true,
]);

$this->form->button('Save', [
    'class' => 'button'
]);
$this->form->end();
