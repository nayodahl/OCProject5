[![Codacy Badge](https://api.codacy.com/project/badge/Grade/5d0bddca95ba4ce3bb5daaa24e5ba77b)](https://app.codacy.com/manual/nayodahl/OCProject5?utm_source=github.com&utm_medium=referral&utm_content=nayodahl/OCProject5&utm_campaign=Badge_Grade_Dashboard)

# OPENCLASSROOMS Blog Project 5 - Create your first own Blog with PHP

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


## Getting started

- Clone Repository
- Install dependancies using Composer, without using --dev flag if you don't need them
- Create a database on your SQL server and import blog_demodata.sql file
- Configure access to this database in file src\Service\Database.php
- Configure your mail credentials on your PHP server, as this blog is using php mail function to send mails. I used a https://mailtrap.io/ inbox during the development 
- Configure your contact mail on file src\Controller\FrontOffice\AccountController.php, on line 19 :

```php

class AccountController
{
    private const CONTACT_MAIL = 'contact@blog.exemple.com';
    private const SERVER_URL = 'www.blog.exemple.com';

```
Messages from contact form will be sent to this address.

- Username from blog_demodata.sql : AntoineD or DavidC (member profile), aurelm or fannyb (admin profile), anthonyf (superadmin profile)
- Password for all users is @dmIn123

## Author

**Anthony Fachaux** - Openclassrooms Student - Dev PHP/Symfony