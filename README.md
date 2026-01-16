# SimpleFinance Manager (PHP + MySQL + PDO)

A beginner-friendly finance tracker where you can:

- Register / Login / Logout (session-based)
- Add income and expense transactions
- See totals (income, expenses, net profit/loss)
- View daily / monthly / yearly summaries
- Export your data to CSV
- Print a clean report (browser print / “PDF-style”)

## 1) Requirements

- PHP 8.x (7.4+ should also work)
- MySQL 5.7+ / 8.0+
- A local server (XAMPP/WAMP/Laragon) or PHP built-in server

## 2) Database Setup

1. Create a database (example name: `simplefinance`).
2. Import the schema:

- Open phpMyAdmin and run `schema.sql`, or
- Use MySQL CLI:

```sql
SOURCE schema.sql;
```

## 3) Configure DB Connection (`db.php`)

This project ships with `db.example.php`.

1. Copy `db.example.php` to `db.php`
2. Open `db.php` and edit:

- `$dbHost`
- `$dbName`
- `$dbUser`
- `$dbPass`

Example:

```php
$dbHost = '127.0.0.1';
$dbName = 'simplefinance';
$dbUser = 'root';
$dbPass = '';
```

## 6) Push to GitHub

### Step 1: Create a repo on GitHub

- Create a new repository (example name: `simplefinance-manager`)
- Do not add a README on GitHub (we already have one locally)

### Step 2: Initialize Git locally and push

Run these commands in your project folder:

```bash
git init
git add .
git commit -m "Initial commit"
git branch -M main
git remote add origin https://github.com/YOUR_USERNAME/YOUR_REPO.git
git push -u origin main
```

### Important

- `db.php` is ignored by Git (see `.gitignore`) so you don’t accidentally upload your local DB password.
- Make sure `db.php` exists on your machine (copy from `db.example.php`).

## 4) Run the Project

### Option A: XAMPP/WAMP/Laragon

- Put this project folder inside your web root (e.g. `htdocs`).
- Visit:

`http://localhost/tit/`

### Option B: PHP Built-in Server

From the project folder, run:

```bash
php -S localhost:8000
```

Then open:

`http://localhost:8000/`

## 5) Pages

- `register.php` - Create account
- `login.php` - Login
- `dashboard.php` - Totals + transactions list + filters
- `add_transaction.php` - Add income/expense
- `profile.php` - View user info
- `export_csv.php` - Download CSV
- `report.php` - Printable report

## Notes

- Passwords are stored securely using `password_hash()`.
- This project is intentionally simple and does not include advanced security features like CSRF tokens (good next step to learn).
