<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Introduction

This project appears to be a Laravel-based web application and RestAPI for Android Application, utilizing various technologies such as PHP, JavaScript, and CSS. The application is a task management system with features like task management, dashboard analytics, application connectivity with api's and user profiles.

## Project Structure

The project structure is based on the Laravel framework, with the following directories and files:

- `app`: Contains the application's core logic, including models, controllers, and middleware.
- `bootstrap`: Holds the application's bootstrapping files, such as the `app.php` file, which configures the application.
- `config`: Stores configuration files for the application, including the `flasher.php` file.
- `database`: Contains the database migration files and the `seeds` directory for populating the database.
- `public`: Holds the application's public-facing files, including the `index.php` file, which serves as the entry point for the application.
- `resources`: Contains the application's resources, such as views, JavaScript files, and CSS files.
- `routes`: Defines the application's routes, including the `console.php` file, which handles console commands.
- `storage`: Stores the application's storage files, including the `framework` directory, which contains the application's framework files.
- `tests`: Holds the application's tests, including unit tests and feature tests.
- `vendor`: Contains the application's dependencies, installed via Composer.

## Installation and Setup

To set up the project, follow these steps:

- Clone the repository to your local machine.
- Install the required dependencies using Composer by running the command composer install in the project's root directory.
- Create a new database for the application and configure the database connection in the .env file.
- Run the database migrations using the command php artisan migrate to create the necessary tables.
- Seed the database with sample data using the command php artisan db:seed.
- Start the development server using the command php artisan serve.

## API's Documentation & Instructions

Postman Collection file name is `TaskManagement.postman_collection.json` located in root directory import it into postman and run the api's according those instruction :point_down:


### Authentication Routes

> In Authentications we use `required` key to indication what we have to do after a successfully response.

Login: `POST /login`
- Request Body:
    - `email`: string (required)
    - `password`: string (required)
- Response:
    - `status`: Boolen (Response Status)
    - `message`: string (Shows what thing done by api)
    - `required`: string (What to do next)
    - `data`: object|array (Desired Data)


Registration: `POST /registration`
- Request Body:
    - `name`: string (required)
    - `email`: string (required)
    - `password`: string (required)
- Response:
    - `status`: Boolen (Response Status)
    - `message`: string (Shows what thing done by api)
    - `required`: string (What to do next)
    - `data`: object|array (Desired Data)

> After Registration user need to varify email by entering otp that they will receive on their mail


OTP Verification: `POST /otp/verification/{mode}`
- Request Body:
    - `otp`: integer (required)
    - `data.name`: string (given on registration's response)
    - `data.email`: string (given on registration's response)
    - `data.otp`: integer (given on registration's response)
    - `data.expired_at`: date (given on registration's response)
    - `data.password`: string (given on registration's response)
- Response:
    - `status`: Boolen (Response Status)
    - `message`: string (Shows what thing done by api)
    - `required`: string (What to do next)
    - `data`: object|array (Desired Data)

Mode: key specify that Which porpose api is use form like `welcome` or `reset`.


Forget Password: `POST /forget/password`
- Request Body:
    - `email`: string (required)
- Response:
    - `status`: Boolen (Response Status)
    - `message`: string (Shows what thing done by api)
    - `required`: string (What to do next)
    - `data`: object|array (Desired Data)


Reset Password: `POST /reset/password`
- Request Body:
    - `password`: string (required)
    - `confirm_password`: string (required)
- Response:
    - `status`: Boolen (Response Status)
    - `message`: string (Shows what thing done by api)
    - `required`: string (What to do next)
    - `data`: object|array (Desired Data)


Resend OTP: `GET /resend-otp`
- Request Body: 
    - `email`: string (required)
- Response:
    - `status`: Boolen (Response Status)
    - `message`: string (Shows what thing done by api)
    - `required`: string (What to do next)
    - `data`: object|array (Desired Data)
    

> Check email api's can be use for real time email checking. that this is exists or not in database.


Check Email: `GET /check/email`
- Request Body: 
    - `email`: string (required)
- Response:
    - `status`: Boolen (Response Status)
    - `message`: string (Shows what thing done by api)
    - `exists`: Boolen (Email Exists or not in database)


Required Key indicates Those Values:

- `Valid Email` : User need enter valid email.
- `Valid credentials` : User need enter valid credentials.
- `OTP Verification` : Need to verify OTP, throw user to otp verify screen.
- `Corect OTP` : User enter wrong otp, He/she need to enter again.
- `Resend OTP` : User's otp is expried, he/she need to get otp again then try.
- `Reset Password` : user can reset their password.
- `null` : nothing is Required.



### Panel Routes

Dashboard: `GET /dashboard`
- Request Body: None
- Response:
    - `status`: Boolen (Response Status)
    - `message`: string (Shows what thing done by api)
    - `data`: object|array (Desired Data)


Profile Info: `GET /profile/info`
- Request Body: None
- Response:
    - `status`: Boolen (Response Status)
    - `message`: string (Shows what thing done by api)
    - `data`: object|array (Desired Data)


Profile Update: `POST /profile/update`
- Request Body:
    - `name`: string (required)
    - `email`: string (required)
    - `profile image`: image (optional)
- Response:
    - `status`: Boolen (Response Status)
    - `message`: string (Shows what thing done by api)
    - `data`: object|array (Desired Data)


Password Update: `POST /password/update`
- Request Body:
    - password: string (required)
    - confirm_password: string (required)
- Response:
    - `status`: Boolen (Response Status)
    - `message`: string (Shows what thing done by api)
    - `data`: object|array (Desired Data)


Task Store: `POST /task/add`
- Request Body:
    - name: string (required)
    - description: string (required)
    - start_date: date (required)
    - due_date: date (required)
- Response:
    - `status`: Boolen (Response Status)
    - `message`: string (Shows what thing done by api)
    - `data`: object|array (Desired Data)


Task List: `GET /task/list`
- Request Body: 
    - status: integer (optional)
    - start: date (optional)
    - due: date (optional)
- Response:
    - `status`: Boolen (Response Status)
    - `message`: string (Shows what thing done by api)
    - `data`: object|array (Desired Data)


Task Status: `GET /task/status/{id}`
- Request Body: None
- Response:
    - `status`: Boolen (Response Status)
    - `message`: string (Shows what thing done by api)
    - `data`: object|array (Desired Data)

> In id parameter we need to pass list array id to change status that specific entry


Task Edit: `GET /task/edit/{id}`
Request Body: None
- Response:
    - `status`: Boolen (Response Status)
    - `message`: string (Shows what thing done by api)
    - `data`: object|array (Desired Data)

> In id parameter we need to pass list array id to get that specific entry


Task Update: `POST /task/update/{id}`
- Request Body:
    - name: string (required)
    - description: string (required)
    - start_date: date (required)
    - due_date: date (required)
- Response:
    - `status`: Boolen (Response Status)
    - `message`: string (Shows what thing done by api)
    - `data`: object|array (Desired Data)

> In id parameter we need to pass list array id to get that specific entry


Task Cancel: `POST /task/cancel/{id}`
- Request Body: None
- Response:
    - `status`: Boolen (Response Status)
    - `message`: string (Shows what thing done by api)
    - `data`: object|array (Desired Data)

> In id parameter we need to pass list array id to Cancel that specific entry


Task Delete: `DELETE /task/delete/{id}`
- Request Body: None
- Response:
    - `status`: Boolen (Response Status)
    - `message`: string (Shows what thing done by api)
    - `data`: object|array (Desired Data)

> In id parameter we need to pass list array id to delete that specific entry