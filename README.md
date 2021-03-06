
# Blog Project 5 - Create your first own Blog with PHP [![Codacy Badge](https://api.codacy.com/project/badge/Grade/5d0bddca95ba4ce3bb5daaa24e5ba77b)](https://app.codacy.com/manual/nayodahl/OCProject5?utm_source=github.com&utm_medium=referral&utm_content=nayodahl/OCProject5&utm_campaign=Badge_Grade_Dashboard)

Blog project for OPENCLASSROOMS, done with Vanilla PHP

## What is this Blog ?

This blog is made of only PHP (except Twig as Template engine and a Bootstrap theme) using MVC architecture.
This is intentionnal and for a learning purpose, before starting to use PHP frameworks.
This means it has a custom router (inspired by Altorouter), custom Session management, custom Authentication and registration system etc...
Here are the rules that needed to be followed : 

* The blog has pages for visitors, and pages for administration.
* Admin section is only for registred users that have admin privilege.
* Commenting a post is only for registred users.
* Each new comment has to be approved by and admin.
* Posts can be created, edited and deleted only by admins. He can change title, chapo, content and author of every Post.
* Users rights can be modified by a Superadmin (giving or removing admin rights).

This blog is online here : https://blog.nayo.cloud and can be tested


## Getting started

- Clone Repository
- Install dependancies using Composer with dev depandancies (composer install , https://getcomposer.org/doc/01-basic-usage.md)
- Generates autoload file with Composer (composer dump-autoload, https://getcomposer.org/doc/01-basic-usage.md)
- Create a database on your SQL server and import blog_demodata.sql file
- Configure access to this database in config file config.ini (db_name, db_user, db_password)
- Configure your mail setup on your PHP server, as this blog is using php mail function to send mails. I used a https://mailtrap.io/ inbox during the development 
- Configure your contact mail and the server url on config file config.ini
Messages from contact form will be sent to this address.


## Let's go

- Username from blog_demodata.sql : AntoineD or DavidC (member profile), aurelm or fannyb (admin profile), anthonyf (superadmin profile)
- Password for all users is @dmIn123

## Author

**Anthony Fachaux** - Openclassrooms Student - Dev PHP/Symfony