<?php
use App\Entity\User;
/**
 * @var User $User
 */
$this->element('test');

echo $this->form->create($User);

echo $this->form->input('username', [
    'placeholder' => 'Enter username',
]);

echo $this->form->input('email', [
    'type' => 'email',
    'placeholder' => 'Enter email',
]);

echo $this->form->input('password', [
    'type' => 'password',
    'placeholder' => 'Enter password',
]);

echo $this->form->input('image', [
    'placeholder' => 'Enter image',
]);

echo $this->form->input('summary', [
    'placeholder' => 'Enter summary',
]);

echo $this->form->button('Save', [
    'class' => 'button'
]);

echo $this->form->end();
