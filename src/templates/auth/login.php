<?php

echo $this->form->create('user');

echo $this->form->input($User, 'email', [
    'type' => 'email',
    'class' => 'test',
    'label' => [
        'class' => 'test',
        'text' => 'email',
    ],
]);

echo $this->form->input($User, 'name', [
    'type' => 'text',
    'class' => 'test',
    'label' => [
        'class' => 'test',
        'text' => 'name',
    ],
]);

echo $this->form->input($User, 'age', [
    'type' => 'number',
    'class' => 'test',
    'label' => [
        'class' => 'test',
        'text' => 'age',
    ],
]);

echo $this->form->input($User, 'password', [
    'type' => 'password',
    'class' => 'test',
    'label' => [
        'class' => 'test',
        'text' => 'password',
    ],
]);

echo $this->form->submit();
echo $this->form->end();
