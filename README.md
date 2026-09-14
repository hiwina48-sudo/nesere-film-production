# NESERE Film Production — PHP/MySQL System

## Technology
PHP 8+, MySQL/MariaDB, Bootstrap 5, HTML5, CSS3, JavaScript, XAMPP.

## Installation
1. Install XAMPP and start **Apache** and **MySQL**.
2. Copy the `nesere_system` folder into `C:\xampp\htdocs\`.
3. Open phpMyAdmin and import `database.sql`.
4. Check `config/config.php` for database credentials.
5. Open `http://localhost/nesere_system/`.

## Admin
Default admin email: `admin@nesere.local`

The seeded password is the standard PHP password-hash example used in this package. If it does not work on your environment, create an admin by updating a registered user role to `admin` in phpMyAdmin.

## Main modules
- Public cinematic website: Home, About, Services, Portfolio, Packages, Contact
- Customer registration/login/logout
- Secure password hashing and session authentication
- Service request/booking with file upload validation
- Customer request/status dashboard
- Admin dashboard
- Service management
- Package management
- Portfolio management
- Customer management
- Request status workflow
- Quotation creation and customer quotation visibility
- Messages and notifications database
- CSRF protection, prepared statements, output escaping, upload validation

## Requirement coverage
The structure follows the supplied NESERE requirements: black/gold/white brand, nine services, customer workflow, admin workflow, database entities, responsive design and the requested PHP/MySQL/XAMPP stack.
