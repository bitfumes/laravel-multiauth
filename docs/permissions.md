# Permissions

### 1. Create a new `crud permissions` for any model:

```bash{1}
php artisan multiauth:permissions {model}
```

Here `{model}` means for which model you want to create crud permissions for.
For example, if you run `php artisan multiauth:permissions blog` then it will create following permissions:

- CreateBlog
- ReadBlog
- UpdateBlog
- DeleteBlog

### 2. You can create single permission for any model

```bash{1}
php artisan multiauth:permission --name={permissionName} {model}
```

or example, if you run `php artisan multiauth:permissions blog --name=Publish`:
It will create a permission in your database called `PublishBlog`

::: tip Assign permission to role
When you create new `role` then you can define `permissions` that role can have

:::
