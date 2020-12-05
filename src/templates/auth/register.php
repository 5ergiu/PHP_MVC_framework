<?php

use App\Core\View;
use App\Entity\User;
/**
 * @var User $User
 * @var View $this
 */

echo $this->form->create($User);

echo $this->form->input('username', [
    'type' => 'text',
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
    'type' => 'text',
    'placeholder' => 'Enter image',
]);

echo $this->form->input('summary', [
    'type' => 'text',
    'placeholder' => 'Enter summary',
]);

echo $this->form->button('Save', [
    'class' => 'button',
    'type' => 'submit',
]);

echo $this->form->end();
