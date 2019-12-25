# Publishing things

`php artisan multiauth:Install` command published everything but if you want, you can publish everything one by one also

- Publish Configurations

```bash{1}
php artisan vendor:publish --tag="multiauth:config"
```

- If you have added new column to admin migration then you should need admin factory to generate first super admin via above command.

```bash{1}
php artisan vendor:publish --tag="multiauth:factories"
```

- Publish Migrations

```bash{1}
php artisan vendor:publish --tag="multiauth:migrations"
```

- Publish Views

```bash{1}
php artisan vendor:publish --tag="multiauth:views"
```

- Publish Route File

```bash{1}
php artisan vendor:publish --tag="multiauth:routes"
```
