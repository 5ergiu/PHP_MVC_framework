<?php

$this->element('test');

echo $this->form->create($User);

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

echo $this->form->submit();
echo $this->form->end();
