# Laravel Multi Auth

-   **Laravel**: 5.6
-   **Author**: Bitfumes
-   **Author Homepage**: http://bitfumes.com

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

Run the Migration to have tables in your database
```bash
php artisan migrate
```

## Publishes
You can publish [the migration](https://github.com/s-sarthak/laravel-multiauth/database/migrations/create_permission_tables.php) with:



You can publish Views files to overrise with yours:

```bash
php artisan vendor:publish --tag="multiauth:views"
```

```bash
php artisan vendor:publish --tag="multiauth:factories"
```

```bash
php artisan vendor:publish --tag="multiauth:config"
```

### Caveat
Please remember that you need to run php artisan make:auth for having some routes like login or register for normal users, otherwise there may be some error that register route not found.
There is no relation with admin side for make:auth, its just for removing error.

### License

This package inherits the licensing of its parent framework, Laravel, and as such is open-sourced
software licensed under the [MIT license](http://opensource.org/licenses/MIT)
