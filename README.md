# Lumen-RESTful-api-for-users-contacts
RESTful API, built in PHP with the [Lumen](https://lumen.laravel.com/ "Lumen page") (micro-framework by Laravel) and Redis cache. It performs CRUD operations using HTTP verbs, for user contacts data.

## Minimum Requirements

* PHP: 7.2
* Composer
* Lumen: 5.8
* PHPUnit: 7.0
* Redis: 5.0.5
* MySQL: 5.7

## Instalation

Clone the repository

```bash
$ git@github.com:edigar/Lumen-RESTful-api-for-users-contacts.git lumen-api
$ cd lumen-api
```

Create a .env file from .env.example and enter the database and Redis settings as bellow.

```bash
$ cat .env.example > .env
```

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=homestead
DB_USERNAME=homestead
DB_PASSWORD=secret

CACHE_DRIVER=redis
```

Install the application dependencies, with compose.

```bash
$ cd api
$ composer install
```

Create the database in your database manager, as below.

```SQL
CREATE DATABASE `apiusers` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

If you have not configured the database access data yet, you can add the database access data, such as database name, username and password in the .env file, locate on /api and run the migration on the console

```bash
$ php artisan migrate
```

Finally, run the php server to load the application

```bash
$ php -S localhost:8000 -t public
```

## Calling API

You can use [Postman](https://www.getpostman.com/ "Postman website"), in order to make API requests, with the HTTP verbs (GET, POST, PUT DELETE). The routes for the calls are /api/users and /api/users/{id}, plus some optional paging parameters.

Below is the list of available calls.

### HTTP Request & Response Service

**REQUEST (Listing all users)**
```
GET /api/users
headers: Content-Type: application/json
body: none
```
**RESPONSE**
```
HTTP 200
```
```
{
    "current_page": ,   //Current page number
    "data": [],         //Array of users data object
    "first_page_url": , //URL link to the fisrt page
    "from": ,           //Number of first user in this page
    "last_page": ,      //Number of last page
    "last_page_url": ,  //URL link to the last page
    "next_page_url": ,  //URL link to the next page
    "path": ,           //Current page path (link)
    "per_page": ,       //Amount user per page
    "prev_page_url": ,  //URL link to the previous page
    "to": ,             //Number of last user in this page
    "total":            //Amount of users
}
```

**REQUEST (Getting data from a single user)**
```
GET /api/users/{id}
headers: Content-Type: application/json
body: none
```
**RESPONSE**
```
HTTP 200
```
```
{
    {
    "name": ,               //User name
    "email": ,              //User e-mail
    "celphone_number": ,    //User celphone number
    "phone_number": ,       //User phone number
    "instagram_url": ,      //User instagram url
    "facebook_url": ,       //User facebook url
    "twitter_url": ,        //User twitter url
    "created_at": ,         //Date and time of user creation
    "updated_at": ,         //Date and time of user data update
    "links": {
        "self":             //URL link of this user
    }
}
```

**REQUEST (Creating a new user)**

*Obs.: email and celphone_number are required*

```
POST /api/users
headers: Content-Type: application/json
body:
{
    "name": "User name",
    "email": "email@email.com",
    "celphone_number": "(99)999999999",
    "phone_number": "(99)99999999",
    "instagram_url": "instagram.com/[user]",
    "facebook_url": "facebook.com/[user]",
    "twitter_url": "twitter.com/[user]",
}
```
**RESPONSE**
```
HTTP 201
```
```
{
    {
    "name": "User name",
    "email": "email@email.com",
    "celphone_number": "(99)999999999",
    "phone_number": "(99)99999999",
    "instagram_url": "instagram.com/[user]",
    "facebook_url": "facebook.com/[user]",
    "twitter_url": "twitter.com/[user]",
    "updated_at": "[DateTime of update data user]",
    "created_at": "[DateTime of create data user]",
    "links": {
        "self": "/api/users/[id]"
    }
}
```

**REQUEST (Updating a user)**
```
PUT /api/users/{id}
headers: Content-Type: application/json
body:
{
    "celphone_number": "(11)111111111",
    "instagram_url": "instagram.com/[new user]",
}
```
**RESPONSE**
```
HTTP 200
```
```
{
    {
    "name": "User name",
    "email": "email@email.com",
    "celphone_number": "(11)111111111",
    "phone_number": "(99)99999999",
    "instagram_url": "instagram.com/[new user]",
    "facebook_url": "facebook.com/[user]",
    "twitter_url": "twitter.com/[user]",
    "updated_at": "[DateTime of update data user]",
    "created_at": "[DateTime of create data user]",
    "links": {
        "self": "/api/users/[id]"
    }
}
```

**REQUEST (Deleting a user)**
```
DELETE /api/users/{id}
headers: Content-Type: application/json
body: none
```
**RESPONSE**
```
HTTP 200
```

### Pagination

In addition to the navigation links on pages that the API offers on response of "GET /api/users", you can call a specific page as well as change the number of users per page, by adding page and per_page parameters.

**Example**
```
GET /api/users?page=2,per_page=5
headers: Content-Type: application/json
body: none
```
**RESPONSE**
```
HTTP 200
```
```
{
    "current_page": 2,  //Current page number
    "data": [],         //Array of users data object
    "first_page_url": , //URL link to the fisrt page
    "from": ,           //Number of first user in this page
    "last_page": ,      //Number of last page
    "last_page_url": ,  //URL link to the last page
    "next_page_url": ,  //URL link to the next page
    "path": ,           //Current page path (link)
    "per_page": 5,      //Amount user per page
    "prev_page_url": ,  //URL link to the previous page
    "to": ,             //Number of last user in this page
    "total":            //Amount of users
}
```

### ERRORS

In case of a call from a single user, that does not exist, the response will be:

```
HTTP 204
```

For any other errors, you will get the response 400, 404 and 500 of HTTP.

## Tests

If you want, you can create a .env.testing file with another database configuration which will be used during tests. Anyway, don't be worry, all data generated by test are deleted.

In order to test, run on root
```bash
$ api/vendor/bin/phpunit
```

## Next steps

As a next step, the goal is to implement a functional docker-compose script with NGINX to make it easier to install the development environment.