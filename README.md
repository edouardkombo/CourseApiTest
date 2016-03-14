psp
===

Technical test purpose.

Create a basic course management using a REST API. Developed by Edouard Kombo.

A live demo of this technical test is available on my vps here: http://vps249035.ovh.net/app_dev.php/course-api/doc

TIME: 2h00.


Technical choice
================

I choosed Symfony 3.0. I am comfortable with Symfony, I have made many projects with this great framework, so it is pleasure to come back to it again.
The goal is to build a Rest architecture with authentication and make any other supports to connect to this api, like mobiles, SPA frameworks etc.
One the best method for this purpose is to use token authentication (we can discuss about it on a private session).

To go faster on authentication process I decided to use FOSUserBundle that already provides everythings we need.
No unit tests have been implemented (lack of time, and it was not the purpose of the test).

I could have choose Nginx, but I decided (it's rare to use apache for this demo).

Bundles choosed:
- FOSUserBundle => For user management
- FOSRestBundle => For Rest architecture management
- JMSSerializerBundle => For Data queries serialization (json, xml)
- NelmioApiDocBundle  => To generate a fast and readable documentation for the api
- Lexik/JWT-Authentication-Bundle => To manage the http authentication by Token

And that's all.


START
=====

Clone the project from github

    git clone https://github.com/edouardkombo/CourseApiTest psp


APACHE CONF
===========

    <VirtualHost *:80>
        ServerName vps249035.ovh.net
        ServerAlias vps249035.ovh.net

        RewriteEngine On
        RewriteCond %{HTTP:Authorization} ^(.*)
        RewriteRule .* - [e=HTTP_AUTHORIZATION:%1]

        DocumentRoot /var/www/psp/web
        <Directory /var/www/psp/web>
            AllowOverride None
            Order Allow,Deny
            Allow from All

            <IfModule mod_rewrite.c>
                Options -MultiViews
                RewriteEngine On
                RewriteCond %{REQUEST_FILENAME} !-f
                RewriteRule ^(.*)$ app.php [QSA,L]
            </IfModule>
        </Directory>

        # uncomment the following lines if you install assets as symlinks
        # or run into problems when compiling LESS/Sass/CoffeScript assets
        # <Directory /var/www/project>
        #     Options FollowSymlinks
        # </Directory>

        # optionally disable the RewriteEngine for the asset directories
        # which will allow apache to simply reply with a 404 when files are
        # not found instead of passing the request into the full symfony stack
        <Directory /var/www/project/web/bundles>
            <IfModule mod_rewrite.c>
                RewriteEngine Off
            </IfModule>
        </Directory>
        ErrorLog /var/log/apache2/default_error.log
        CustomLog /var/log/apache2/default_access.log combined
    </VirtualHost>


HOW TO DO
=========

1. Create a database with specified credentials

    mysql -u root -p
    CREATE DATABASE test

2. Go at the root of the project and install needed dependencies (Nelmio Api Doc, JMS Serializer Bundle, FOSUSerBundle, FOSRestBundle and Lexik JWT Authentication Bundle)

    composer.phar install
    chmod -R 777 var/cache && chmod -R 777 var/logs

Take a look at src/UserBundle/Entity and src/CourseBundle/Entity, both contains based fields of each tables with ManyToMany relations.


3. Now, you can create the database schema

    bin/console doctrine:schema:create


4. Test database creation in mysql

    mysql -u root -p
    SHOW TABLES;
    USE test;
    DESCRIBE test;


5. In order to use the api, we need to authenticate, I prefered Http token authentication which is multipurpose (Mobile, etc).
So we need to create a user from FOSUserBundle, create a user with name "edouardo" and password "test".

    bin/console fos:user:create


6. Test user creation in mysql

    mysql -u root -p
    use test;
    SELECT * FROM user;


7. Now, you have to get the http token by logging in (use any Rest client)

    http://localhost/app_dev.php/api/login_check

    Set as paramaters (username: edouardo, password: test) and carefully copy the token


8. Get full list of Api urls and description

    http://localhost/app_dev.php/course-api/doc


9. Test the api

    - Use any Rest client that accepts headers with Authorization parameter. You'll have to set in header "Authorization: Bearer __your_token__"
    - Or go here http://localhost/app_dev.php/course-api/doc, click on a method and on the sandbox submenu. Inside Headers, write "Authorization => Bearer __your_token__"