# Autovex
This repository demonstrates a simple e-commerce cart system built with Laravel (backend) and a Vue 3 + Vite + Storybook frontend for displaying product cards.

## Features

- Product management with database migrations and seeders
- Cart functionality with the ability to add products and calculate totals
- Comprehensive test suite for cart operations
- Product card component with Storybook integration

## Requirements

- PHP 8.1+
- Composer
- Node.js and NPM
- MySQL/PostgreSQL

## Installation

1. Clone the repository
   ```bash
   git clone https://github.com/esa-kian/autovex
   cd autovex
   ```

2. Install PHP dependencies
   ```bash
   composer install
   ```

3. Install Node.js dependencies
   ```bash
   npm install
   ```

4. Create a `.env` file
   ```bash
   cp .env.example .env
   ```

5. Configure your database connection in `.env`

6. Generate application key
   ```bash
   php artisan key:generate
   ```

7. Run migrations and seed the database
   ```bash
   php artisan migrate --seed
   ```

## Running Tests

```bash
php artisan test
```

## Running Storybook

```bash
npm run storybook
```

## License
This project is open-sourced software licensed under the MIT license.