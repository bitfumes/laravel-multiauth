# Laravel Multi Auth

-   **Laravel**: 5.6
-   **Author**: Bitfumes
-   **Author Homepage**: https://bitfumes.com

[![Build Status](https://travis-ci.org/s-sarthak/laravel-multiauth.svg?branch=master)](https://travis-ci.org/s-sarthak/laravel-multiauth)
[![StyleCI](https://github.styleci.io/repos/143331251/shield?branch=master)](https://github.styleci.io/repos/143331251)
[![Total Downloads](https://poser.pugx.org/bitfumes/laravel-multiauth/downloads)](https://packagist.org/packages/bitfumes/laravel-multiauth)
[![GitHub license](https://img.shields.io/github/license/s-sarthak/laravel-multiauth.svg)](https://github.com/s-sarthak/laravel-multiauth/blob/master/LICENSE.md)
[![GitHub stars](https://img.shields.io/github/stars/s-sarthak/laravel-multiauth.svg)](https://github.com/s-sarthak/laravel-multiauth/stargazers)

This package is just create admin side (multi auth), which is totaly isolated from your normal auth ( which we create using php artisan make:auth )

On top of that, you can use multiple authentication types, simultaneously, so you can be logged
in as a user and an admin, without conflicts!

## Installation

Install via composer.

```bash
composer require bitfumes/multiauth
```

Publish Migrations
```bash
php artisan vendor:publish --tag="multiauth:migrations"
```

Run [the Migration](https://github.com/s-sarthak/laravel-multiauth/database/migrations/create_permission_tables.php) to have tables in your database and with one super admin
```bash
php artisan migrate
php artisan multiauth:seed --role=super
```

Now you can login your admin side by going to https://localhost:8000/admin with creadential of email = 'super@admin.com' and password = 'secret'.
Obviously you can later change these things.

## Register new Admin

To register new use you need to go to https://localhost:8000/admin/register
**Keep in mind that only a Super Admin can create new Admin.**

### Changing admin views
You can Publish package views files and overrides with yours so that you can have views which suits your project design.
```bash
php artisan vendor:publish --tag="multiauth:views"
```

## Admin table migrations

Sometime you need to add new column to Admin table, just publish the package Admin migration to add new columns.
```bash
php artisan vendor:publish --tag="multiauth:factories"
```

### Validations
Yes you can write validation rules to your new columns or change existing validation rules by publishing config file.
```bash
php artisan vendor:publish --tag="multiauth:config"
```

## Create Roles
To create a new role you have two options:
1. Using artisan command
```bash
php artisan multiauth:role rolename
```

2. Using Interface
Just go to https://localhost:8000/admin/role.

Now you can click on 'Add Role' button to create new role.

**Edit or Delete Role can also be done with same interface**

<!-- ### Caveat
Please remember that you need to run php artisan make:auth for having some routes like login or register for normal users, otherwise there may be some error that register route not found.
There is no relation with admin side for make:auth, its just for removing error. -->

### License
This package inherits the licensing of its parent framework, Laravel, and as such is open-sourced
software licensed under the [MIT license](http://opensource.org/licenses/MIT)
