1. Clone github repository
2. Otvori: C:\Windows\System32\drivers\etc\hosts
3. Dodaj na kraj file-a:
	127.0.0.1.		github.search
4. Otvori: C:\xampp\apache\conf\extra\httpd-vhosts.conf
5. Dodaj na kraj file-a:
	<VirtualHost *:80>
    ServerName github.test
    DocumentRoot "C:\xampp\htdocs\laravel\github_search\public"
    SetEnv APPLICATION_ENV "development"
    <Directory "C:\xampp\htdocs\laravel\github_search\public">
        DirectoryIndex index.php
        Order allow,deny
        Allow from all
    </Directory>
</VirtualHost>
6. Otvori root folder projekta u cmd-u, i pokreni sljedeće naredbe:
	composer install
	php artisan migrate

7. Change .env variables:
	DB_CONNECTION=mysql
	DB_HOST=127.0.0.1
	DB_PORT=3306
	DB_DATABASE=db_github_search
	DB_USERNAME=root
	DB_PASSWORD=

8. Create database (localhost/phpmyadmin)
9. php artisan migrate
