## Wedding Website Builde

A Laravel-based web application that allows users to create, customize, and publish wedding websites using pre-designed templates.

## Features

Template-based wedding website builder
Dynamic JSON content management
Image gallery upload
Public publish & share link
User dashboard
Admin panel
Role-based authentication

## Tech Stack

Backend: Laravel (PHP)
Frontend: Blade + jQuery + AJAX
Database: MySQL
Storage: Laravel Public Storage
Authentication: Laravel Auth

## Installation
--------------------------------------
## Requirements
PHP 8.1+
Composer
MySQL

1. Clone Repository

2. Install Dependencies
composer install

3. Environment Setup
cp .env.example .env
php artisan key:generate
Update database credentials in .env:

4. Run Migrations
php artisan migrate
(Optional)-php artisan db:seed(for dummy payment data)

5. Run Application
php artisan serve
Visit: http://localhost:8000

## Application Flow

Admin creates master templates.
User selects a template.
User customizes content.
On publish, a unique route is generated:  /published/{route}


## User Roles
 
## User
Create and manage wedding templates
Upload gallery images
Publish wedding website

## Admin
Manage users
Manage payments
Manage master templates

## Security

Auth middleware
Role-based access control
CSRF protection
Ownership validation

