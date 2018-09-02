# Laravel Multi Auth

- **Laravel**: 5.6/5.7
- **Author**: Bitfumes
- **Author Homepage**: https://bitfumes.com

[![Build Status](https://scrutinizer-ci.com/g/s-sarthak/laravel-multiauth/badges/build.png?b=master)](https://scrutinizer-ci.com/g/s-sarthak/laravel-multiauth/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/s-sarthak/laravel-multiauth/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/s-sarthak/laravel-multiauth/?branch=master)
[![Maintainability](https://api.codeclimate.com/v1/badges/69d079cd52b7b2fb94c4/maintainability)](https://codeclimate.com/github/s-sarthak/laravel-multiauth/maintainability)
[![StyleCI](https://github.styleci.io/repos/143331251/shield?branch=master)](https://github.styleci.io/repos/143331251)
[![Total Downloads](https://poser.pugx.org/bitfumes/laravel-multiauth/downloads)](https://packagist.org/packages/bitfumes/laravel-multiauth)
[![GitHub license](https://img.shields.io/github/license/s-sarthak/laravel-multiauth.svg)](https://github.com/s-sarthak/laravel-multiauth/blob/master/LICENSE.md)
[![GitHub stars](https://img.shields.io/github/stars/s-sarthak/laravel-multiauth.svg)](https://github.com/s-sarthak/laravel-multiauth/stargazers)
[![Latest Stable Version](https://poser.pugx.org/bitfumes/laravel-multiauth/v/stable)](https://packagist.org/packages/bitfumes/laravel-multiauth)
[![GitHub issues](https://img.shields.io/github/issues/s-sarthak/laravel-multiauth.svg)](https://github.com/s-sarthak/laravel-multiauth/issues)


This package is just create admin side (multi auth), which is totaly isolated from your normal auth ( which we create using php artisan make:auth )

On top of that, you can use multiple authentication types, simultaneously, so you can be logged
in as a user and an admin, without conflicts!

## Installation

Install via composer.

```bash
composer require bitfumes/laravel-multiauth
```

Before proceeding : you can make a normal auth, check [this doc](https://github.com/s-sarthak/laravel-multiauth#another-auth)

Publish Migrations

```bash
php artisan vendor:publish --tag="multiauth:migrations"
```

Run [the Migration](https://github.com/s-sarthak/laravel-multiauth/database/migrations/create_permission_tables.php) to have tables in your database.

```bash
php artisan migrate
```

**Caveat**

If you have added new column to admin migration then you should need admin factory to generate first super admin via above command.

```bash
php artisan vendor:publish --tag="multiauth:factories"
```

## First Admin

Obviously, first time you need at least one super admin to login with and then create other admins.

```
php artisan multiauth:seed --role=super
```

Now you can login your admin side by going to https://localhost:8000/admin with creadential of **email = super@admin.com** and **password = secret**
Obviously you can later change these things.

## Register new Admin

To register new use you need to go to https://localhost:8000/admin/register.

Keep in mind that only a Super Admin can create new Admin.

**Changing admin views**

You can Publish package views files and overrides with yours so that you can have views which suits your project design.

```bash
php artisan vendor:publish --tag="multiauth:views"
```

**Validations**

Yes you can write validation rules to your new columns or change existing validation rules by publishing config file.

```bash
php artisan vendor:publish --tag="multiauth:config"
```

## Change Prefix

You can change the prefix in your config file you have just published.
With prefix we mean what you want to call your admin side, we call it admin you can call it whatever you want.
Suppose you have changed prefix to 'master' now everywhere instead of 'admin' word, that changed to 'master'

```php
 /*
    |--------------------------------------------------------------------------
    | Prefix
    |--------------------------------------------------------------------------
    |
    | Use prefix to before the routes of multiauth package.
    | This way you can keep your admin page secure.
    | Default : admin
    */
    'prefix' => 'admin', // can change it to, lets say 'prefix' => 'master'
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

## Publish Routes file

```bash
php artisan vendor:publish --tag="multiauth:routes"
```

## Access Level

**With Middleware**

1. You can use 'role' middleware to allow various admin for accessing certain section according to their role.

```php
Route::get('admin/check',function(){
    return "This route can only be accessed by admin with role of Editor"
})->middleware('role:editor');
```

Here it does't matter if you give role as uppercase or lowercase or mixed, this package take care of all these.

2. If you want a section to be accessed by only super user then use role:super middleware
   A super admin can access all lower role sections.

```php
Route::get('admin/check',function(){
    return "This route can only be accessed by super admin"
})->middleware('role:super');
```

**With Blade Syntax**

You can simply use blade syntax for showing or hiding any section for admin with perticular role.
For example, If you want to show a button for admin with role of editor then write.

```php
@admin('editor')
    <button>Only For Editor</button>
@endadmin
```

## Another Auth

**Apart from Admin section, you can make a another auth**

```php
php artisan multiauth:make {guard}
```

After you run this command you will get steps in which files has been added/changed.
![For Make](https://user-images.githubusercontent.com/41295276/44602450-4a4e2580-a7fd-11e8-858b-cac65c496908.png)

**You can rollback this auth also if you want.**

```php
php artisan multiauth:rollback {guard}
```

This command will show you steps to rollback and file that has changed/removed.
![For Rollback](https://user-images.githubusercontent.com/41295276/44602466-5508ba80-a7fd-11e8-9737-3711baecbbdb.png)

## License

This package inherits the licensing of its parent framework, Laravel, and as such is open-sourced
software licensed under the [MIT license](http://opensource.org/licenses/MIT)
