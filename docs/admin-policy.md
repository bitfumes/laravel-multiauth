# Admin Policy

## Access by Permission on controller

You can use policy to protect admin from doing unauthorized stuffs
Like in any controller you can check

```php{2}
...
$this->authorize('CreateRole', Admin::class);
...
```
