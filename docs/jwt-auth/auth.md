# Authentication Endpoints

## Authentication

Login

```bash{1,2}
POST /admin/login
PAYLOAD: email,password
```

Logout

```bash{1}
POST /admin/logout
```

## Password Reset

Send password reset link email

```bash{1,2}
POST /password/email
PAYLOAD: email
```

Reset Password

```bash{1,2}
POST /password/reset
PAYLOAD: email, password, password_confirmation, token
```

> `token` you can get from the password reset link user clicked
