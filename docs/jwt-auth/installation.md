# JWT Version Installation

Install via `composer`.

```bash{1}
composer require bitfumes/laravel-multiauth:dev-jwtauth
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
