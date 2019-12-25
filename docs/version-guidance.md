# Version Guidance

## JWT API version

- If you want to use this package while creating API, you can install `jwt-auth` version.
- This branch has all the api with proper response and no views or blade files
- This version uses `tymon/jwt` to create authentication token

```bash{1}
composer require bitfumes/laravel-multiauth:dev-jwtauth
```

::: tip JWT Auth Docs
Check out full documentation for JWT Auth version [Click Here](https://bitfumes.github.io/laravel-multiauth/jwt-auth/installation.html)

> Or you can switch documentation from navbar `branch` tab

:::

## Laravel Versions

| Laravel version    | Branch | Install                                               |
| ------------------ | ------ | ----------------------------------------------------- |
| 5.4                | 5.4    | composer require bitfumes/laravel-multiauth:5.4.x-dev |
| 5.5                | 5.5    | composer require bitfumes/laravel-multiauth:5.5.x-dev |
| 5.6, 5.7, 5.8, 6.0 | Master | composer require bitfumes/laravel-multiauth           |
