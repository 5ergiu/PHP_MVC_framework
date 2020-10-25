<?php

echo $this->Form->create('user');

echo $this->Form->input($User, 'email', [
    'type' => 'email',
    'class' => 'test',
    'label' => [
        'class' => 'test',
        'text' => 'email',
    ],
]);

echo $this->Form->input($User, 'name', [
    'type' => 'text',
    'class' => 'test',
    'label' => [
        'class' => 'test',
        'text' => 'name',
    ],
]);

echo $this->Form->input($User, 'age', [
    'type' => 'number',
    'class' => 'test',
    'label' => [
        'class' => 'test',
        'text' => 'age',
    ],
]);

echo $this->Form->input($User, 'password', [
    'type' => 'password',
    'class' => 'test',
    'label' => [
        'class' => 'test',
        'text' => 'password',
    ],
]);

echo $this->Form->submit();
echo $this->Form->end();
