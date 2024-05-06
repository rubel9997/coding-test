<h1 align="center">Bank Management System</h1>

## Introduction
Bank Management System is a Laravel application aimed at streamlining banking transaction management for users. Through this platform, users can effortlessly create accounts, deposit and withdraw funds, and access transaction history. This comprehensive documentation offers a detailed walkthrough for setting up the project and configuring essential banking features.

### Prerequisites
- PHP 8.2 or higher
- Composer
- Laravel 11
- MySQL or any other supported database system

## Setup Instructions

### Step 1: Clone the Repository
```
git clone https://github.com/rubel9997/coding-test.git
cd coding-test
```

### Step 2: Install Dependencies
```bash
composer install
```

### Step 3: Configure Environment Variables
Duplicate the `.env.example` file and rename it to `.env`. Update the following variables:

```ini
DB_CONNECTION=mysql
DB_HOST=your_database_host
DB_PORT=your_database_port
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password

```

### Step 4: Generate Application Key
```bash
php artisan key:generate
```

### Step 5: Run Migrations and Seed Database
```bash
php artisan migrate
```

### Step 6: Serve the Application
```bash
php artisan serve
```

Access the application in your browser at `http://localhost:8000`.

