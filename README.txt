KCA MindConnect - Full ZIP Package (PHPMailer-ready)

Instructions:
1) Create the database:
   - Run the SQL in init.sql (use phpMyAdmin or mysql CLI)
2) Configure environment:
   - Edit config.php or set environment variables:
     DB_HOST, DB_NAME, DB_USER, DB_PASS
     MAIL_HOST, MAIL_USERNAME, MAIL_PASSWORD, MAIL_PORT, MAIL_FROM, MAIL_FROM_NAME
3) Install PHPMailer (recommended):
   - Run `composer require phpmailer/phpmailer` in the project root
   - This creates vendor/autoload.php used by send_mail()
   - If PHPMailer is not installed, the app falls back to PHP mail()
4) Place project in your web root (e.g., htdocs/kca_mindconnect_full) and visit /public/register.php
5) For Gmail SMTP: enable 2FA, create an App Password, use it as MAIL_PASSWORD

Files included:
- config.php
- init.sql
- public/*.php, style.css, script.js
- api/*.php
