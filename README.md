# 🎓 School Management System

A comprehensive school management system built with Laravel 11, Livewire 3, and Bootstrap 5. Features include student management, course enrollment, grading, attendance tracking, and a complete REST API for mobile applications.

## 📋 Table of Contents

- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Database Setup](#database-setup)
- [Running the Application](#running-the-application)
- [API Documentation](#api-documentation)
- [Testing](#testing)
- [Deployment](#deployment)
- [Project Structure](#project-structure)
- [Technologies Used](#technologies-used)
- [License](#license)

## ✨ Features

### Web Application
- 👥 **User Management** - Admin, Teacher, and Student roles with permissions
- 📚 **Course Management** - Create and manage courses with offerings
- 🎓 **Student Management** - Complete student profiles with registration
- 📝 **Enrollment System** - Automated course enrollment based on class
- 📊 **Grading System** - Flexible grade schemes and components
- 📅 **Attendance Tracking** - Track student attendance by course
- 📄 **Assignments** - Create and manage course assignments
- 📈 **Dashboard** - Role-specific dashboards with statistics
- 🎨 **Modern UI** - Clean, responsive interface with dark mode support

### REST API (Mobile)
- 🔐 **Token Authentication** - Laravel Sanctum for secure API access
- 📱 **100+ Endpoints** - Complete CRUD operations for all features
- 🔄 **Bulk Operations** - Efficient batch processing
- 📖 **Full Documentation** - Comprehensive API documentation with examples
- 🚀 **Mobile Ready** - Optimized for Flutter and React Native

## 🔧 Requirements

- **PHP** >= 8.1
- **Composer** >= 2.0
- **Node.js** >= 18.x
- **NPM** >= 9.x
- **MySQL** >= 8.0 or **SQLite**
- **Git**

## 📥 Installation

### 1. Clone the Repository

```bash
git clone https://github.com/yourusername/ScoolProject.git
cd ScoolProject
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node Dependencies

```bash
npm install
```

### 4. Copy Environment File

```bash
cp .env.example .env
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

## ⚙️ Configuration

### Environment Variables

Edit the `.env` file with your configuration:

```env
# Application
APP_NAME="School Management"
APP_ENV=local
APP_KEY=base64:YOUR_GENERATED_KEY
APP_DEBUG=true
APP_URL=http://localhost

# Database (MySQL)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=school_db
DB_USERNAME=root
DB_PASSWORD=

# Or use SQLite for local development
# DB_CONNECTION=sqlite
# DB_DATABASE=/absolute/path/to/database.sqlite

# Mail Configuration (optional)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Database Choice

#### Option 1: MySQL (Recommended for Production)

Create a MySQL database:

```bash
mysql -u root -p
CREATE DATABASE school_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

#### Option 2: SQLite (Quick Local Development)

```bash
touch database/database.sqlite
```

Update `.env`:
```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/ScoolProject/database/database.sqlite
```

## 🗄️ Database Setup

### Run Migrations

```bash
php artisan migrate
```

This will create all necessary tables:
- users
- students
- courses
- course_offerings
- enrollments
- grades
- grade_components
- grade_schemes
- classes
- attendances
- assignments
- files
- audit_logs
- roles & permissions (Spatie)
- personal_access_tokens (Sanctum)

### Seed Database (Optional)

```bash
php artisan db:seed
```

This will create:
- Default admin user
- Sample roles and permissions
- Test courses
- Sample students and enrollments

### Default Credentials

After seeding, you can login with:

```
Email: admin@example.com
Password: password
```

## 🚀 Running the Application

### Development Server

Start the Laravel development server:

```bash
php artisan serve
```

The application will be available at: `http://localhost:8000`

### Compile Frontend Assets

#### Development Mode (Watch for changes)

```bash
npm run dev
```

#### Production Build

```bash
npm run build
```

### Run Both Simultaneously

In one terminal:
```bash
php artisan serve
```

In another terminal:
```bash
npm run dev
```

## 📚 API Documentation

The REST API is fully documented and ready for mobile application integration.

### Access Documentation

- **API Documentation**: `API_DOCUMENTATION.md` - Complete endpoint reference
- **API README**: `API_README.md` - Quick start guide and examples
- **Postman Collection**: `POSTMAN_COLLECTION.md` - Ready-to-import collection

### API Base URL

**Local Development:**
```
http://localhost:8000/api/v1
```

**Production:**
```
https://school.ashuzadestin.space/api/v1
```

### Quick API Test

```bash
# Test login endpoint
curl -X POST http://localhost:8000/api/v1/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password"}'
```

### API Features

- ✅ Token-based authentication (Laravel Sanctum)
- ✅ 100+ RESTful endpoints
- ✅ Pagination on all list endpoints
- ✅ Advanced filtering and search
- ✅ Bulk operations support
- ✅ Role-based access control
- ✅ Consistent JSON responses

## 🧪 Testing

### Run All Tests

```bash
php artisan test
```

### Run Specific Test Suite

```bash
# Feature tests
php artisan test --testsuite=Feature

# Unit tests
php artisan test --testsuite=Unit
```

### Code Coverage

```bash
php artisan test --coverage
```

## 📦 Deployment

### Production Checklist

- [ ] Set `APP_ENV=production` in `.env`
- [ ] Set `APP_DEBUG=false` in `.env`
- [ ] Configure production database
- [ ] Set up SSL/HTTPS
- [ ] Configure mail server
- [ ] Run `composer install --optimize-autoloader --no-dev`
- [ ] Run `npm run build`
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Run `php artisan view:cache`
- [ ] Set up cron job for scheduled tasks
- [ ] Configure queue workers
- [ ] Set proper file permissions

### Server Requirements

```bash
# Set permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Web server should point to /public directory
# Example Nginx config:
root /var/www/school/public;
```

### Optimization Commands

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize
```

### Scheduled Tasks (Cron)

Add to crontab:

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

### Queue Workers (Optional)

If using queues, set up supervisor:

```bash
php artisan queue:work --daemon
```

## 📁 Project Structure

```
ScoolProject/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── API/              # REST API Controllers
│   │   │   │   ├── AuthController.php
│   │   │   │   ├── StudentController.php
│   │   │   │   ├── CourseController.php
│   │   │   │   └── ...
│   │   │   └── HomeController.php
│   │   └── Middleware/
│   │       └── EnsureRole.php    # Role-based access
│   ├── Livewire/                 # Livewire Components
│   │   ├── Students/
│   │   ├── Courses/
│   │   ├── Grades/
│   │   ├── Dashboards/
│   │   └── ...
│   ├── Models/                   # Eloquent Models
│   │   ├── User.php
│   │   ├── Student.php
│   │   ├── Course.php
│   │   ├── Grade.php
│   │   └── ...
│   └── Policies/
│       └── GradePolicy.php
├── database/
│   ├── migrations/               # Database migrations
│   ├── seeders/                  # Database seeders
│   └── factories/                # Model factories
├── resources/
│   ├── views/                    # Blade templates
│   │   ├── layouts/
│   │   └── livewire/
│   ├── js/                       # JavaScript files
│   │   └── app.js
│   ├── sass/                     # SASS styles
│   │   └── app.scss
│   └── css/
├── routes/
│   ├── web.php                   # Web routes
│   ├── api.php                   # API routes
│   └── console.php
├── tests/
│   ├── Feature/                  # Feature tests
│   └── Unit/                     # Unit tests
├── public/                       # Public assets
├── storage/                      # File storage
├── API_DOCUMENTATION.md          # API docs
├── API_README.md                 # API guide
├── POSTMAN_COLLECTION.md         # Postman collection
└── README.md                     # This file
```

## 🛠️ Technologies Used

### Backend
- **Laravel 11** - PHP framework
- **Livewire 3** - Full-stack framework for Laravel
- **Spatie Laravel Permission** - Role and permission management
- **Laravel Sanctum** - API authentication

### Frontend
- **Bootstrap 5** - CSS framework
- **Bootstrap Icons** - Icon library
- **Alpine.js** - Minimal JavaScript framework (via Livewire)
- **Vite** - Frontend build tool

### Database
- **MySQL** - Primary database (production)
- **SQLite** - Alternative for development
- **Eloquent ORM** - Database abstraction

### Development Tools
- **Composer** - PHP dependency manager
- **NPM** - Node package manager
- **Git** - Version control

## 🔐 Security

- CSRF protection on all forms
- SQL injection protection via Eloquent
- XSS protection
- Password hashing with bcrypt
- Token-based API authentication
- Role-based access control
- Input validation on all requests

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License.

## 👨‍💻 Authors

- **Your Name** - Initial work

## 🙏 Acknowledgments

- Laravel community
- Livewire team
- Bootstrap team
- All contributors

## 📞 Support

For support, email support@example.com or open an issue in the repository.

## 🔗 Links

- **Production**: https://school.ashuzadestin.space
- **API Documentation**: See `API_DOCUMENTATION.md`
- **Bug Reports**: [GitHub Issues](https://github.com/yourusername/ScoolProject/issues)

---

**Made with ❤️ using Laravel**

