# Admin Endpoints

## Admin Crud

All Admin from database

```bash{1}
GET /admin/all
```

Get Loggedin Admin

```bash{1}
GET /admin/me
```

Create New Admin

```bash{1,2}
POST /admin/register
PAYLOAD name, email, password, password_confirmation, role_ids, permission_ids
```

> Here `permission_ids` is for direct permissions and is optional.

Update Admin Details

```bash{1,2}
PATCH /admin/{admin_id}
PAYLOAD name, email, role_ids, permission_ids
```

> Here `permission_ids` is for direct permissions and is optional.

Delete Admin Details

```bash{1}
DELETE /admin/{admin_id}
```

## Activations

Make Admin Active

```bash{1}
POST /admin/activation/{admin_id}
```

Make Admin InActive

```bash{1}
DELETE /admin/activation/{admin_id}
```

## Password Update

Delete Admin Details

```bash{1,2}
PATCH /admin/{admin_id}
PAYLOAD oldPassword, password, password_confirmation
```
