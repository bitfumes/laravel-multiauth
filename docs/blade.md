# Blade Syntax

You can simply use blade syntax for showing or hiding any section for admin with perticular role.

#### 1. Using `permitTo` blade syntax

You can add access level using `permitTo` middleware

```php{1,3}
@permitTo('CreateBlog')
    <button>Only For Admin with permission of `CreateBlog`</button>
@endpermitTo
```

> Make sure you write permission name exactly as it is, createblog and CreateBlog treated as two different permissions

#### 2. Using `permitToParent` blade syntax

Suppose you want to allow access for any CRUD permission of blog, for example if admin has any permission among create, read, update or delete for blog.

```php{1,3}
@permitToParent('Blog')
    <button>Only For Admin with any permission for `Blog` model </button>
    <span>Visible only if admin has any permissions among `Create, Read, Update or Delete` for blog</span>
@endpermitTo
```

## Access by Role

#### 3. Using `role` blade syntax

For example, If you want to show a button for admin with role of editor then write.

```php{1,3}
@admin('editor')
    <button>Only For Editor</button>
@endadmin
```

#### 4. Role of `super` admin

If you want to add multiple role, you can do like this

```php{1,3}
@admin('editor,publisher,any_role')
    <button> This is visible to admin with all these role</button>
@endadmin
```
