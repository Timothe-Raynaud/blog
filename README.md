# Installing project

- Set configuration information on src/Config/Config.php 
- Composer install
- There is a directories name 'sql' for create database.

- config the server for redirect to public/index.php
- exemple (on .conf - docker :
  RewriteEngine On
  RewriteBase /
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteRule ^(.+)$ index.php [QSA,L])
