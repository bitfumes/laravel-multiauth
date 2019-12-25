# Installation

Install via `composer`.

```bash{1}
composer require bitfumes/laravel-multiauth
```

### Setup Admin Authentication

Setup `Configurations`, `Migrations` and `seed` super admin with `role` and `permissions`

::: tip Note: Database connection
Make sure you setup your database connection on `.env` file before running install command.
:::

```bash{1}
php artisan multiauth:install
```

::: tip Above command do 4 things

1. Publishing Configurations
2. Publishing Migrations
3. Running Migrations
4. Seeding New Super Admin
   - This also create a role called `super`
   - Create permissions for `CRUD` of `Admin` and `Role`

So you will have a super admin with email as `super@admin.com` and password as `secret123`
:::

### For laravel 6 and above only

- With `Laravel 6`, the Views part is now exclueded into a package.
- To have your UI or bootstrap theme on your project for this package, install official `laravel-ui` package

Make sure you check this package's docs, **[Here](https://laravel.com/docs/6.x/frontend)**

```bash{1}
composer require laravel/ui --dev
```

Then you can Generate basic scaffolding...

```bash{1,3,5}
php artisan ui bootstrap
// or
php artisan ui vue
// or
php artisan ui react
```

:) **And its done** :)
