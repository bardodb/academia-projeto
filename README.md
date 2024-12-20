# Gym Management System

A comprehensive gym management system built with Laravel, featuring student management, check-ins tracking, and payment processing.

## Features

- User Authentication with Multiple Roles (Admin, Instructor, Student)
- Student Management
- Check-in System
- Payment Tracking
- Membership Plan Management
- Modern UI with Tailwind CSS

## Requirements

- PHP 8.1 or higher
- Composer
- MySQL 5.7 or higher
- Node.js and NPM

## Installation

1. Clone the repository
```bash
git clone https://github.com/yourusername/gym-management.git
cd gym-management
```

2. Install PHP dependencies
```bash
composer install
```

3. Install NPM dependencies
```bash
npm install
```

4. Create environment file
```bash
cp .env.example .env
```

5. Configure your database in the .env file
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gym_management
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

6. Generate application key
```bash
php artisan key:generate
```

7. Run database migrations and seeders
```bash
php artisan migrate --seed
```

8. Build assets
```bash
npm run build
```

9. Start the development server
```bash
php artisan serve
```

## Default Users

After running the seeders, you can login with these default accounts:

- Admin:
  - Email: admin@example.com
  - Password: password

- Student:
  - Email: student@example.com
  - Password: password

## Database Structure

The system uses the following main tables:

- `users` - Stores user authentication data
- `students` - Contains student-specific information
- `check_ins` - Records gym attendance
- `plans` - Stores membership plan details
- `payments` - Tracks payment history

## Usage

1. Login as admin using the default credentials
2. Create new students and assign them to plans
3. Use the check-in system to track attendance
4. Monitor payments and plan status
5. Generate reports as needed

## Development

For development, you can use Laravel's built-in tools:

```bash
# Watch for changes in development
npm run dev

# Run tests
php artisan test

# Format code
./vendor/bin/pint
```

## Security

- All routes are protected with appropriate middleware
- Form validation is implemented throughout
- CSRF protection is enabled
- Input sanitization is in place

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

