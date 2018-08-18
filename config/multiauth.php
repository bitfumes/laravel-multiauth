<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Validations
    |--------------------------------------------------------------------------
    |
    | Apply your own validations for new columns of Admin registration.
    |
 */
    'admin' => [
        'validations' => [
            // Write rules here
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Prefix
    |--------------------------------------------------------------------------
    |
    | Use prefix to before the routes of multiauth package.
    | This way you can keep your admin page secure.
    | Default : admin
    */
    'prefix' => 'admin',
];
