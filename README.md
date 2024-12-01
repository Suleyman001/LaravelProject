# 🚀 Laravel Notebook Store Project Requirements Breakdown

### 💻[Live Demo](https://njelaravelproject-hfe8axdzdffubcdc.francecentral-01.azurewebsites.net/)

## Required steps in start to test this app
```bash 
cd C:\xampp\htdocs\Notebook\LaravelProject\laravel
```
```bash
php artisan serve
```
```bash
npm run dev
```
## Diagnostic Commands:
```bash
# Clear all caches
php artisan config:clear
php artisan route:clear
php artisan cache:clear

# Regenerate key
php artisan key:generate

# Optimize
php artisan optimize
```
## 1. Spectacular Responsive Website 🌐  
- **Dynamic Layout Design Principles**
  - Responsive grid system
  - Mobile-first approach
  - Adaptive design techniques
  - Flexible component layouts

## 2. Authentication System 🔐  
- **Multi-Level User Roles**
  - User Levels:
    - Guest
    - Logged-in User
    - Admin
  - **Menu Visibility Strategy**
    - 🌍 Universal Menu Items:
      - Home page
      - Product catalog
      - About us
    - 👤 Logged-in User Menu:
      - Personal dashboard
      - Order history
      - Profile settings
    - 🛡️ Admin Exclusive Menu:
      - User management
      - Product administration
      - Sales analytics

## 3. CRUD Application Features 📋  
- **Content Management Rules**
  - **Guest Users:**
    - View-only access
    - Read permissions
  - **Logged-in Users:**
    - Modify personal content
    - Delete personal entries
  - **Admin Users:**
    - Full CRUD capabilities
    - Manage all system content

## 4. Database Query Form 📊  
- **Form Components**
  - 📝 Text Inputs
  - 🔽 Dropdown Lists
  - 🔘 Radio Buttons
- **Query Requirements**
  - Utilize all database tables
  - Flexible search mechanisms
  - Comprehensive result display

## 5. Data Insertion Page 📥  
- **Server-Side Validation**
  - Input sanitization
  - Type checking
  - Constraint validation
  - Error messaging
  - Secure data insertion

## 6. Chart.js Visualization 📈  
- **Graphing Requirements**
  - Use Chart.js library
  - Dynamic data rendering
  - Interactive chart elements
  - Multiple chart types:
    - Line graphs
    - Bar charts
    - Pie charts

## 🌐 Deployment Requirements
- **Hosting Considerations**
  - PHP version compatibility
  - Laravel framework support
  - Performance optimization
  - SSL certification

## 💻 Technical Setup
- **PHP & Composer Verification**
-##Expected: PHP 8.1+
```-composer --version```

-Expected: Composer 2.x+
 
- **Laravel Project Initialization**
```bash Create Laravel Project
composer create-project laravel/laravel notebook-store
```
Navigate to project
```cd notebook-store```

Start development server
```php artisan serve```

-## 🛠 Key Development Commands
-Generate Authentication Scaffolding
-php artisan make

-Create Models
```bash 
php artisan make
Notebook php artisan make
User  
```
Create Migrations
```bash 
php artisan make
create_notebooks_table php artisan make
create_users_table
```
```bash  
Run Migrations
php artisan migrate
```
```bash 
Create Controllers
php artisan make
NotebookController php artisan make
UserController
```
 

## 🔐 Authentication Flow
- Registration
- Login
- Role Assignment
- Session Management
- Logout

 
## 🌟 Project Milestones
- Project Setup
- Database Design
- Authentication System
- CRUD Implementations
- Frontend Development
- Testing
- Deployment

## 🚦 Deployment Platforms
- Heroku
- DigitalOcean
- AWS Elastic Beanstalk
- Laravel Forge
Feel free to copy this content into your README.md file for your GitHub project!
                    
                    Notebook store Project
##Shorty 
```bash
php -version
composer --version
cd C:\Portable-Laravel-9.0.2-basic
php artisan --version
php artisan
php artisan serve
```

##Detailed
```bash
----------------------- DETAILED-----------------
php -version
        PHP 8.1.29 (cli) (built: Jun  5 2024 10:43:25) (ZTS Visual C++ 2019 x64)
        Copyright (c) The PHP Group
        Zend Engine v4.1.29, Copyright (c) Zend Technologies    
composer --version
        Composer version 2.8.2 2024-10-29 16:12:11
        PHP version 8.1.29 (C:\php\php.exe)
        Run the "diagnose" command to get more detailed diagnostics output.
cd C:\Portable-Laravel-9.0.2-basic
php artisan --version
        Laravel Framework 9.0.2
 php artisan
            Laravel Framework 9.0.2
```
            Usage:
            command [options] [arguments]

            Options:
            -h, --help            Display help for the given command. When no command is given display help for the list command
            -q, --quiet           Do not output any message
            -V, --version         Display this application version
                --ansi|--no-ansi  Force (or disable --no-ansi) ANSI output
            -n, --no-interaction  Do not ask any interactive question
                --env[=ENV]       The environment the command should run under
            -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

            Available commands:
            clear-compiled        Remove the compiled class file
            completion            Dump the shell completion script
            db                    Start a new database CLI session
            down                  Put the application into maintenance / demo mode
            env                   Display the current framework environment
            help                  Display help for a command
            inspire               Display an inspiring quote
            list                  List commands
            migrate               Run the database migrations
            optimize              Cache the framework bootstrap files
            serve                 Serve the application on the PHP development server
            test                  Run the application tests
            tinker                Interact with your application
            up                    Bring the application out of maintenance mode
            auth
            auth:clear-resets     Flush expired password reset tokens
            cache
            cache:clear           Flush the application cache
            cache:forget          Remove an item from the cache
            cache:table           Create a migration for the cache database table
            config
            config:cache          Create a cache file for faster configuration loading
            config:clear          Remove the configuration cache file
            db
            db:seed               Seed the database with records
            db:wipe               Drop all tables, views, and types
            event
            event:cache           Discover and cache the application's events and listeners
            event:clear           Clear all cached events and listeners
            event:generate        Generate the missing events and listeners based on registration
            event:list            List the application's events and listeners
            key
            key:generate          Set the application key
            make
            make:cast             Create a new custom Eloquent cast class
            make:channel          Create a new channel class
            make:command          Create a new Artisan command
            make:component        Create a new view component class
            make:controller       Create a new controller class
            make:event            Create a new event class
            make:exception        Create a new custom exception class
            make:factory          Create a new model factory
            make:job              Create a new job class
            make:listener         Create a new event listener class
            make:mail             Create a new email class
            make:middleware       Create a new middleware class
            make:migration        Create a new migration file
            make:model            Create a new Eloquent model class
            make:notification     Create a new notification class
            make:observer         Create a new observer class
            make:policy           Create a new policy class
            make:provider         Create a new service provider class
            make:request          Create a new form request class
            make:resource         Create a new resource
            make:rule             Create a new validation rule
            make:scope            Create a new scope class
            make:seeder           Create a new seeder class
            make:test             Create a new test class
            migrate
            migrate:fresh         Drop all tables and re-run all migrations
            migrate:install       Create the migration repository
            migrate:refresh       Reset and re-run all migrations
            migrate:reset         Rollback all database migrations
            migrate:rollback      Rollback the last database migration
            migrate:status        Show the status of each migration
            model
            model:prune           Prune models that are no longer needed
            notifications
            notifications:table   Create a migration for the notifications table
            optimize
            optimize:clear        Remove the cached bootstrap files
            package
            package:discover      Rebuild the cached package manifest
            queue
            queue:batches-table   Create a migration for the batches database table
            queue:clear           Delete all of the jobs from the specified queue
            queue:failed          List all of the failed queue jobs
            queue:failed-table    Create a migration for the failed queue jobs database table
            queue:flush           Flush all of the failed queue jobs
            queue:forget          Delete a failed queue job
            queue:listen          Listen to a given queue
            queue:monitor         Monitor the size of the specified queues
            queue:prune-batches   Prune stale entries from the batches database
            queue:prune-failed    Prune stale entries from the failed jobs table
            queue:restart         Restart queue worker daemons after their current job
            queue:retry           Retry a failed queue job
            queue:retry-batch     Retry the failed jobs for a batch
            queue:table           Create a migration for the queue jobs database table
            queue:work            Start processing jobs on the queue as a daemon
            route
            route:cache           Create a route cache file for faster route registration
            route:clear           Remove the route cache file
            route:list            List all registered routes
            sail
            sail:install          Install Laravel Sail's default Docker Compose file
            sail:publish          Publish the Laravel Sail Docker files
            schedule
            schedule:clear-cache  Delete the cached mutex files created by scheduler
            schedule:list         List the scheduled commands
            schedule:run          Run the scheduled commands
            schedule:test         Run a scheduled command
            schedule:work         Start the schedule worker
            schema
            schema:dump           Dump the given database schema
            session
            session:table         Create a migration for the session database table
            storage
            storage:link          Create the symbolic links configured for the application
            stub
            stub:publish          Publish all stubs that are available for customization
            vendor
            vendor:publish        Publish any publishable assets from vendor packages
            view
            view:cache            Compile all of the application's Blade templates
            view:clear            Clear all compiled view files

```bash
composer require laravel/ui
php artisan ui bootstrap --auth
npm install
npm run dev
```

## Download and Install Node.js:
- Go to https://nodejs.org/
- Download the LTS (Long Term Support) version
- Run the installer
- Restart your terminal/command prompt after installatio
## Verify the installation:
  ```bash copy
  node --version
  npm --version
  ```
## After Node.js is installed, go back to your Laravel project directory and run:
``` bash
npm install
npm run dev
```

## some useful informations 
- Open PowerShell as Administrator
- Run this command to check current execution policy:
  ``` bash
  Get-ExecutionPolicy
  ```
- If it's "Restricted", change it to "RemoteSigned":
  ```bash
  Set-ExecutionPolicy RemoteSigned
  ```
- When prompted, type "Y" to confirm
  - or just use other command tools which is better
  
    ```bash
    php artisan migrate:fresh
    php artisan db:seed
    ```
    
