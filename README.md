# Intervals Implementation
## System Requirements
1. PHP 7.0 >
2. MySQL
3. Composer

## Installation
1. Make sure you have composer installed in your host and run
`composer install`
2. Create a new database.
3. You can find MySQL dump in scripts folder, please import database in your MySQL Server.
4. Run `cp .env.example .env`
5. Fill out your DB Details.
6. Edit your php_unit_local.xml with your local host enviroment to run the tests
7. Run `./vendor/bin/phpunit tests/IntervalTest --configuration php_unit_local.xml` to see PHPUnit results

## How to Use
Refer to postman API Documentation.
https://documenter.getpostman.com/view/4658689/SVfKyWzQ

#####Notes: Simple UI and Javascript were used to have a good UI and some API Ajax Implementation




