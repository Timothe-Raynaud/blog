# Installing project

- For dev environment create file 'config/_dev.php' and configure it based on '_prod.php'
- If is for prod configure 'config/_prod.php' and be sure there is no 'config/_dev.php' 
- Composer install
- There is a directories name 'sql' for create database.

- config the server for redirect to public/index.php
- exemple (on .conf - docker :
  RewriteEngine On
  RewriteBase /
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteRule ^(.+)$ index.php [QSA,L])