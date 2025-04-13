# StreamPlus

## Description

StreamPlus app / Multi-Step form

## Prerequisites

Before installing StreamPlus, ensure your development environment includes the following:

- **PHP**: Version 8.2 or higher
- **Composer**: Dependency manager for PHP
- **Database**: [MySQL] version 5.7 or higher

## Installation

### 1. Clone the Repository

```bash
  git clone https://github.com/Triskelabg/StreamPlus.git
  cd StreamPlus
  composer install
```

​### 2. Create a new file named .env.dev and add the following line:​

```bash
  DATABASE_URL="mysql://root:root@127.0.0.1:8889/streamplus?serverVersion=8.0.32&charset=utf8mb4"
```
Alternatively, you can add it directly to your existing .env file.

​### 3. Create database and run migrations :​

```bash
  php bin/console doctrine:database:create
  php bin/console doctrine:migrations:migrate
```

### 3. Launch the Application

```bash
  symfony serve
```

Alternatively, use PHP's built-in server:

```bash
  php -S 127.0.0.1:8000 -t public
```
