<?php
/**
 * Actors model config
 */
return array(
    'title' => 'Users',
    'single' => 'User',
    'model' => 'App\Models\User',
    /**
     * The display columns
     */
    'columns' => array(
        'id',

    ),
    /**
     * The filter set
     */
    'filters' => array(
        'id',

    ),
    /**
     * The editable fields
     */
    'edit_fields' => array(
        'username' => array(
            'title' => 'Username',
            'type' => 'text',
        ),
    ),
    'edit_fields' => array(
        'username' => array(
            'title' => 'Username',
            'type' => 'text',
        ),
        'first_name' => array(
            'title' => 'First name',
            'type' => 'text',
        ),
        'second_name' => array(
            'title' => 'Second name',
            'type' => 'text',
        ),
        'email' => array(
            'title' => 'Email',
            'type' => 'text',
        ),
        'password' => array(
            'title' => 'Password',
            'type' => 'password',
        ),
    ),
);