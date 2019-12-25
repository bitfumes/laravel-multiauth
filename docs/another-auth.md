# Another Auth

## Create

**Apart from Admin section, you can make a another auth**

This is fully compatible with laravel `MustVerifyEmail` trait, so that you can make user to must verify email. [click here](https://laravel.com/docs/5.8/verification) more details

```bash{1}
php artisan multiauth:make {guard}
```

here `{guard}` means the name of your auth. For example, if you want to create another auth for students then you run `php artisan multiauth:make student`

After you run this command you will get steps in which files has been added/changed.
![For Make](https://user-images.githubusercontent.com/41295276/44602450-4a4e2580-a7fd-11e8-858b-cac65c496908.png)

## Rollback

**You can rollback this auth also if you want.**

```bash{1}
php artisan multiauth:rollback {guard}
```

This command will show you steps to rollback and file that has changed/removed.
![For Rollback](https://user-images.githubusercontent.com/41295276/44602466-5508ba80-a7fd-11e8-9737-3711baecbbdb.png)
