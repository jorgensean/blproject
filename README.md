BoldLeads Lead Capture
=======================================

Setup and Install
-----------------
This project was created using the Phalcon MVC framework. Phalcon requires a modern LAMP stack as well as a custom PHP extension.

-https://docs.phalconphp.com/en/3.3/installation - basic installation steps and requirements.

Additionally, a vagrant box is available for faster setup and development.

-https://docs.phalconphp.com/hu/3.3/environments-vagrant

Once Phalcon has been installed as a PHP extension, configure your web servers root directory to point to the applications `public` directory and navigate to the docroot.

Database Configuration
----------------------
A database schema is included as `db.sql`. Once imported, `/app/config/config.php` will need to be updated to reflect database name and login credentials.

Application
-----------
Lead registration landing page is available at the root of the site. To access the Lead dashboard navigate to `http://projectroot/leads`. Contextual navigation is available for searching, pagination, and deleting.

Testing
-------
Unit Testing is done with PHPUnit.

`composer install` from project root to install phpunit and dependencies. Tests can be run by executing `../vendor/bin/phpunit` from within the `test` folder.
