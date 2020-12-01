<?php
use App\Entity\User;
/**
 * @var User $User
 */

$this->form->create($User);

$this->form->input('username', [
    'type' => 'text',
    'placeholder' => 'Enter username',
]);

$this->form->input('email', [
    'type' => 'email',
    'placeholder' => 'Enter email',
]);

$this->form->input('password', [
    'type' => 'password',
    'placeholder' => 'Enter password',
]);

$this->form->input('image', [
    'type' => 'text',
    'placeholder' => 'Enter image',
]);

$this->form->input('summary', [
    'type' => 'text',
    'placeholder' => 'Enter summary',
]);

$this->form->button('Save', [
    'class' => 'button'
]);

$this->form->end();
