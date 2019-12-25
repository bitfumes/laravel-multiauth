# Settings

## Admin Status

Sometime a super admin want to deactivate an admin.

By default new admin is deactivate, so that you can activate him when you want.

To activate admin, just go to the procedure of editing admin.

## Turn Off Admin Login

If you don't want admin authentication and just need to create multiple authentication like /student or /teacher, then you can turn off admin authentication.

```php{13}
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
```

## Form Validations

By default admin registration have validations but if you publish migration and adds any field, then you can provide validation rule for that field here.

```php{10,11,12,13,14}
...
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

...
```

## Prefix for admin

- If you don't want to call admin side as `admin` then you can change it to whatever you want, for example you want your admin side to be as `master`.
- After changing here, your admin route will also changed from `/admin` to `/master`

```php{11}
...
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

...
```

## Registration Notification Email

When super admin register any other admin, then an email will send to welcome new admin with password. You can turn it on or off.

```php{13}
...
    /*
    |--------------------------------------------------------------------------
    | Registration Notification Email
    |--------------------------------------------------------------------------
    |
    | While registering new admin by superadmin, you can send an email to
    | the new registered admin with the password you have selected
    | If you make it 'true' then it will send email otherwise
    | It will not going to send any email to the admin
    | Default : false
    */
    'registration_notification_email' => false,
...
```

## Redirect After Login

By default after login, admin will redirect to admin home page, you can define your own path for redirection after login.

```php{12}
...
    /*
    |--------------------------------------------------------------------------
    | Redirect After Login
    |--------------------------------------------------------------------------
    |
    | It will redirect to a path defined here after login.
    | You can change it to where ever you want to
    | redirect the admin after login.
    | Default : /admin/home
    */
    'redirect_after_login' => '/admin/home',
...
```

## Models

You can set your own model and define here to use by package.

```php{10,11,12,13,14}
...
    /*
    |--------------------------------------------------------------------------
    | Models
    |--------------------------------------------------------------------------
    |
    | Add the model you want to use.
    |
    */
    'models' => [
        'admin'          => Bitfumes\Multiauth\Model\Admin::class,
        'role'           => Bitfumes\Multiauth\Model\Role::class,
        'permission'     => Bitfumes\Multiauth\Model\Permission::class,
    ],
];


```
