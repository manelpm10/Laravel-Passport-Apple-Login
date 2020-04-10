<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Registration Fields
    |--------------------------------------------------------------------------
    |
    | The name of the fields on the user model that need to be updated,
    | if null, they shall not be updated. (valid for name, first_name, last_name)
    |
    */

    'registration' => [
        'apple_id'    => env('APPLE_ID_COLUMN', 'apple_id'),
        'email'       => env('EMAIL_COLUMN', 'email'),
        'password'    => env('PASSWORD_COLUMN', 'password'),
        'first_name'  => env('FIRST_NAME_COLUMN', 'first_name'),
        'last_name'   => env('LAST_NAME_COLUMN', 'last_name'),
        'name'        => env('NAME_COLUMN', 'name'),
        'attach_role' => env('ATTACH_ROLE', null),
    ],
];
