# Installing project

- Set configuration information on src/Config/Config.php 
- Composer install
- Take sql on sql/create_database.sql for create database.
- config the server for redirect to public/index.php: <br>
exemple (<br>
  on .conf - docker :<br>
  RewriteEngine On<br>
  RewriteBase /<br>
  RewriteCond %{REQUEST_FILENAME} !-d<br>
  RewriteCond %{REQUEST_FILENAME} !-f<br>
  RewriteRule ^(.+)$ index.php [QSA,L]<br>)
