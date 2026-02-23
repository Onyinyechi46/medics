# medics

## PHP Authentication App

A secure authentication app built with plain PHP, PDO, MySQL, and PHP sessions is available in `auth-app/`.

### Features
- Registration with email + password
- Email format and password length validation
- Duplicate email prevention
- Password hashing with `password_hash()`
- Login verification with `password_verify()`
- Session-based auth with secure cookie options
- Session ID regeneration on login
- Basic CSRF token protection on forms
- Protected application page
- Logout with session destruction

### Project structure
```
auth-app/
  config/
    database.php
  includes/
    auth.php
  register.php
  login.php
  application.php
  logout.php
  index.php
  schema.sql
```

### Setup
1. Create the database/table:
   ```bash
   mysql -u root -p < auth-app/schema.sql
   ```
2. Update DB credentials in `auth-app/config/database.php` if needed.
3. Start local server:
   ```bash
   php -S localhost:8000 -t auth-app
   ```
4. Open `http://localhost:8000`.
