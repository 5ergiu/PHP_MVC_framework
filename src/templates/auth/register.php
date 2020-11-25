<?php
use App\Entity\User;
/**
 * @var User $User
 */

$this->form->create($User);

$this->form->input('username', [
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
    'placeholder' => 'Enter image',
]);

$this->form->input('summary', [
    'placeholder' => 'Enter summary',
]);

$this->form->button('Save', [
    'class' => 'button'
]);

$this->form->end();
