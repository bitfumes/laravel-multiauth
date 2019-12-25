# Permission Endpoints

Create New Permission

```bash{1,2}
POST /admin/permission
PAYLOAD name,parent
```

::: tip For Parent field
Here `parent` field is the model name for which you are creating permission.
For example, `CreateBlog` permission belongs to parent called `Blog`
Think about parent as the group of permissions.
:::

Get all Permissions

```bash{1}
GET /admin/permission
```

Update A Permissions

```bash{1,2}
PATCH /admin/permission/{permission_id}
PAYLOAD name,parent
```

Delete A Permissions

```bash{1}
DELETE /admin/permission/{permission_id}
```
