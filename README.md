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
cd task-management-api
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

