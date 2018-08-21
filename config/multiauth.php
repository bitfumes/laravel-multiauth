<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Turn Off Admin Login
    |--------------------------------------------------------------------------
    |
    | After creating another multiauth apart from admin, and you don't want to have admin
    | backend then you can simply turn if off by makeing admin_active as false here.
    |
 */
    'admin_active' => true,

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
