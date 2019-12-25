# Middelware

## Access by Permission

#### 1. Using `permitTo` middleware

You can add access level using `permitTo` middleware

```php{3}
Route::get('admin/blog/create',function(){
    return "This route can only be accessed by admin has permission called `CreateBlog`"
})->middleware('permitTo:CreateBlog');
```

You can define more than one permission separated by semicolon ( ; )

```php{3}
Route::get('admin/blog/create',function(){
    return "This route can only be accessed by admin has permission called `CreateBlog`"
})->middleware('permitTo:CreateBlog;ReadBlog;UpdateBlog');
```

> Make sure you write permission name exactly as it is, createblog and CreateBlog treated as two different permissions

#### 2. Using `permitToParent` middleware

Suppose you want to allow access for any CRUD permission of blog, for example if admin has any permission among create, read, update or delete for blog.

```php{3}
Route::get('admin/blog/create',function(){
    return "This route can only be accessed by admin has permission called `CreateBlog`"
})->middleware('permitToParent:Blog');
```

- This means even if admin has permission to `ReadBlog` then also he can access this route.
- This will be helpfull where you want admin with any permission related to `blog` to access.

## Access by Role

#### 3. You can use `role` middleware to allow various admin for accessing certain section according to their role.

```php{3}
Route::get('admin/check',function(){
    return "This route can only be accessed by admin with role of Editor"
})->middleware('role:editor');
```

You can define more than one role separated by semicolon ( ; )

```php{3}
Route::get('admin/check',function(){
    return "This route can only be accessed by admin with role of Editor"
})->middleware('role:editor;publisher');
```

Now this route is available for admin with the role of either `editor` or `publisher` or both.

Here it does't matter if you give role as uppercase or lowercase or mixed, this package take care of all these.

#### 4. If you want a section to be accessed by only super user then use role:super middleware

A super admin can access all lower role sections.

```php{3}
Route::get('admin/check',function(){
    return "This route can only be accessed by super admin"
})->middleware('role:super');
```
