# Task Management API

## About Task Management API

Task Management API is a RESTful backend application built with Laravel.

It provides secure authentication and full task management functionality
including:

-   User authentication using Laravel Sanctum
-   Task CRUD operations
-   Pagination support
-   Request validation
-   API Resource formatting
-   Protected routes using middleware

This API is designed to be consumed by a frontend application such as
React, Vue, or a mobile app.

------------------------------------------------------------------------

## Requirements

-   PHP \>= 8.2
-   Composer
-   Laravel 12+

------------------------------------------------------------------------

## Installation

### Clone the repository

``` bash
git clone https://github.com/rivohenintsoa/task-api.git
cd task-api
```

### Install dependencies

``` bash
composer install
```

### Copy the environment file

``` bash
cp .env.example .env
```

### Generate application key

``` bash
php artisan key:generate
```

### Configure your database inside the `.env` file

``` env
DB_CONNECTION=sqlite
```

### Run migrations

``` bash
php artisan migrate
```

### Run seeders

``` bash
php artisan db:seed
```

### Start the development server

``` bash
php artisan serve
```

The API will be available at:

    http://127.0.0.1:8000/api/v1


### Testing the API with Postman

A Postman collection is included in the repository to simplify API testing (Task Api.postman_collection.json).

1. Open Postman.

2. Import the Task Management API.postman_collection.json file from the repository.

3. Set the environment variables (host, url, token).

4.  You can now test all endpoints including the Task collection endpoints (list, create, update, delete).

5.  Make sure to authenticate using the Sanctum token when testing protected routes.

Note: The Task collection endpoints allow you to fetch tasks assigned to the authenticated user, or all tasks if you are an admin.

