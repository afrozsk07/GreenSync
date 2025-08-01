# GreenSync

A comprehensive web-based environmental management platform built with Laravel that facilitates efficient waste collection, segregation, transportation, and tracking for both users and administrators.

##  Live Demo

- **Production**: [https://greensync.vercel.app](https://greensync.vercel.app)
- **Staging**: [https://greensync-staging.vercel.app](https://greensync-staging.vercel.app)

##  Features

### User Features
- **Collection Requests** - Submit pickup requests for waste collection
- **Real-time Tracking** - Track collection status and progress in real-time
- **Waste Segregation** - Proper categorization and segregation of different waste types
- **Transportation Monitoring** - Monitor waste transportation from collection to disposal
- **Profile Management** - Update personal information and address details
- **Dashboard** - Overview of collection history and statistics

### Admin Features
- **Collection Management** - Manage and assign collection requests to drivers
- **Segregation Oversight** - Monitor waste segregation processes
- **Transportation Management** - Coordinate waste transportation logistics
- **Driver Management** - Manage driver assignments and vehicle allocations
- **Dashboard Analytics** - Comprehensive overview of system operations

##  Technology Stack

### Backend
- **Laravel 12.0** - PHP web application framework
- **PHP 8.2+** - Server-side programming language
- **SQLite** - Lightweight database for development

### Frontend
- **Blade Templates** - Laravel's templating engine
- **Tailwind CSS 4.0** - Utility-first CSS framework
- **Vite** - Build tool and development server
- **Axios** - HTTP client for API requests

### Development Tools
- **Laravel Vite Plugin** - Asset compilation
- **PHPUnit** - Testing framework
- **Concurrently** - Development process management

##  Prerequisites

Before running this application, make sure you have the following installed:

- **PHP 8.2 or higher**
- **Composer** (PHP package manager)
- **Node.js** (for frontend asset compilation)
- **npm** or **yarn** (package managers)

##  Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/greensync.git
   cd greensync
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Build frontend assets**
   ```bash
   npm run build
   ```

##  Running the Application

### Development Mode
```bash
# Start the development server
php artisan serve

# In another terminal, start the asset watcher
npm run dev
```

### Production Mode
```bash
# Build assets for production
npm run build

# Start the server
php artisan serve
```

The application will be available at `http://localhost:8000`

##  User Accounts

### Default Admin Account
- **Email**: admin@wastemanagement.com
- **Password**: admin123

### Default User Account
- **Email**: xyz@gmail.com
- **Password**: xyz123

##  Project Structure

```
GreenSync/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/          # Admin controllers
│   │   ├── Auth/           # Authentication controllers
│   │   └── User/           # User controllers
│   ├── Models/             # Eloquent models
│   └── Providers/          # Service providers
├── config/                 # Laravel configuration
├── database/
│   ├── migrations/         # Database migrations
│   └── seeders/           # Database seeders
├── public/                 # Public assets
├── resources/
│   ├── views/             # Blade templates
│   │   ├── admin/         # Admin views
│   │   ├── auth/          # Authentication views
│   │   └── user/          # User views
│   ├── css/               # Stylesheets
│   └── js/                # JavaScript files
├── routes/
│   └── web.php            # Web routes
├── storage/                # Laravel storage
├── tests/                  # Test files
├── vendor/                 # Composer dependencies
├── .env.example           # Environment example
├── artisan                # Laravel artisan
├── composer.json          # PHP dependencies
├── package.json           # Node dependencies
├── vercel.json           # Vercel configuration
└── README.md             # Project documentation
```

##  Configuration

### Database Configuration
The application uses SQLite by default. You can modify the database configuration in `config/database.php` and update the `.env` file accordingly.

### Environment Variables
Key environment variables in `.env`:
- `APP_NAME` - Application name
- `APP_ENV` - Environment (local, production)
- `APP_DEBUG` - Debug mode
- `DB_CONNECTION` - Database connection type
- `DB_DATABASE` - Database name

##  Testing

Run the test suite:
```bash
php artisan test
```

##  API Documentation

The application provides RESTful APIs for:
- User authentication and registration
- Waste collection requests
- Collection tracking
- Transportation management
- Admin operations

##  Deployment

### Vercel Deployment

1. **Install Vercel CLI**
   ```bash
   npm i -g vercel
   ```

2. **Login to Vercel**
   ```bash
   vercel login
   ```

3. **Deploy to Vercel**
   ```bash
   vercel --prod
   ```

### Environment Variables for Vercel

Set these environment variables in your Vercel dashboard:

```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-project-name.vercel.app
DB_CONNECTION=sqlite
DB_DATABASE=/tmp/database.sqlite
CACHE_DRIVER=file
SESSION_DRIVER=file
SESSION_LIFETIME=120
QUEUE_CONNECTION=sync
```

### GitHub Actions (Optional)

Create `.github/workflows/deploy.yml` for automatic deployment:

```yaml
name: Deploy to Vercel
on:
  push:
    branches: [main]
jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - uses: amondnet/vercel-action@v20
        with:
          vercel-token: ${{ secrets.VERCEL_TOKEN }}
          vercel-org-id: ${{ secrets.ORG_ID }}
          vercel-project-id: ${{ secrets.PROJECT_ID }}
          working-directory: ./
```

##  Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

##  License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

##  Support

If you encounter any issues or have questions:
- Create an issue in the repository
- Contact the development team
- Check the Laravel documentation for framework-specific questions

##  Version History

- **v1.0.0** - Initial release with basic waste management features
- User and admin authentication
- Waste collection request system
- Collection tracking functionality
- Transportation management
- Segregation oversight

---

**Built with ❤️ using Laravel** 