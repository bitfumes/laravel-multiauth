# Laravel Multi Auth

-   **Laravel**: 5.6
-   **Author**: Bitfumes
-   **Author Homepage**: http://bitfumes.com

This package is just create admin side (multi auth), which is totaly isolated from your normal auth ( which we create using php artisan make:auth )

On top of that, you can use multiple authentication types, simultaneously, so you can be logged
in as a user and an admin, without conflicts!

## Installation

Install via composer.

```bash
composer require bitfumes/multiauth
```

You can publish [the migration](https://github.com/s-sarthak/laravel-multiauth/database/migrations/create_permission_tables.php) with:

```bash
php artisan vendor:publish --tag="multiauth:migrations"
```

You can publish Views files to overrise with yours:

```bash
php artisan vendor:publish --tag="multiauth:views"
```

### License

This package inherits the licensing of its parent framework, Laravel, and as such is open-sourced
software licensed under the [MIT license](http://opensource.org/licenses/MIT)
