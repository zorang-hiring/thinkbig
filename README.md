## Books Application

Application can import and search books.

Architecture is highly flexible. SOLID principles are followed. It should be 
very easy to add new File parsers and search criteria.

### Requirements

- php > 8.0
- MySQL db

## Installation

1. create and update .env file (user .env.example as template)
2. `composer install`
3. `php artisan migrate:refresh --seed`

## Application run

- `php artisan serve`

## Usage

### Register

POST /api/register

### Login

POST /api/login

On success login it will return TOKEN to use for Books Import and Books Search.

Note: To login as Admin use the user "admin@gmail.com" and pass "pass123".
 
### Import Books

POST /api/import-books

**Note, you have to use Bearer Authentication 
by Token which you will get on Admin login ("an_admin_token").**

### Search Books

GET /api/books

or with params:

GET /api/books?name=some+name&period=max-5-years

Available periods are:
- max-5-years
- max-10-years
- more-then-10-years

## Tests

`vendor/phpunit/phpunit/phpunit`

APP code is covered with tests ~100% ! See https://take.ms/KbsAo 
