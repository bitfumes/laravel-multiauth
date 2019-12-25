# Role Endpoints

Get All Roles from database

```bash{1}
GET /admin/role
```

Create New Role

```bash{1,2}
GET /admin/role/store
PAYLOAD name, permissions
```

> Here `permissions` is the array of permission ids

Update Role

```bash{1,2}
PATCH /admin/role/{role_id}
PAYLOAD name, permissions
```

Delete Role

```bash{1,2}
DELETE /admin/role/{role_id}
PAYLOAD name, permissions
```
