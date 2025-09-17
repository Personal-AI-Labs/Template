# Modern PHP MVC Docker Starter Kit

A clean, modern, and minimal starter template for building PHP web applications. This project provides a solid, object-oriented MVC architecture running in a fully containerized Docker environment, complete with a robust authentication system and other modern development features.

It's designed to be a strong foundation, allowing you to skip the boilerplate setup and start building your application's unique features immediately.

## Core Features

- **Dockerized Environment**: Fully containerized with Docker Compose for consistent development and easy setup (PHP 8.3-Apache & PostgreSQL 15).
- **Modern MVC Architecture**: A lightweight, custom-built Model-View-Controller framework that promotes separation of concerns.
- **PSR-4 Autoloading**: Uses Composer's industry-standard autoloader for clean, namespaced code.
- **Dependency Injection**: A simple but effective DI pattern is used to provide controllers with necessary dependencies, like the database connection.
- **Singleton Database Wrapper**: A robust Singleton pattern ensures a single, efficient database connection (PDO) throughout the application's lifecycle.
- **Advanced Routing**: A custom router that supports route groups and middleware for protecting authenticated routes.
- **Full Authentication System**: Includes ready-to-use logic and views for:
    - User Registration with Email Verification
    - Login & Logout
    - Password Reset (Forgot Password) Flow
- **Environment-Based Configuration**: Uses .env files for managing sensitive credentials and environment-specific settings.
- **Database Seeding**: Automatically seeds the database with initial data on first run.

## Tech Stack

- **Backend**: PHP 8.3
- **Web Server**: Apache
- **Database**: PostgreSQL 15
- **Containerization**: Docker & Docker Compose
- **Dependencies**: vlucas/phpdotenv

## Getting Started

Follow these steps to get your local development environment up and running.

### 1. Prerequisites

- Git
- Docker
- Docker Compose (usually included with Docker Desktop)

### 2. Clone the Repository

```bash
git clone <your-repository-url>
cd <your-project-directory>
```

### 3. Set Up Your Environment File

The application uses a `.env` file to store all configuration variables. An example file is provided.

```bash
# Copy the example file
cp .env.example .env
```

Now, open the newly created `.env` file and customize the variables if needed. The defaults are generally fine for local development.

```env
# / .env

# Application Environment
APP_ENV=development
APP_DEBUG=true

# PostgreSQL Database Configuration
POSTGRES_HOST=postgres
POSTGRES_PORT=5432
POSTGRES_DB=postgres
POSTGRES_USER=postgres
POSTGRES_PASSWORD=password
```

### 4. Build and Run the Containers

This command will download the necessary images, build your custom PHP application image, and start the containers in the background.

```bash
docker compose up -d --build
```

The first time you run this, Docker will also initialize the PostgreSQL database and run the SQL files located in the `/database` directory to create the schema and seed the users table.

### 5. Access the Application

Your application should now be running!

- **Website**: http://localhost:8080
- **Database**: You can connect to the PostgreSQL database on localhost at port 5432 using the credentials from your .env file.

You can log in with the default admin user:
- **Email**: admin@example.com
- **Password**: password123

## Architecture Overview

This template follows a classic Front Controller and MVC pattern.

### Directory Structure

```
.
├── database/            # Database schema and seed files
│   ├── schema.sql
│   └── seeds/
├── docker/              # Docker-specific configurations
│   ├── apache/
│   └── app/
├── public/              # Web server root, public assets
│   ├── assets/
│   ├── views/           # All PHP view templates
│   └── index.php        # The application's single entry point (Front Controller)
├── src/                 # All application source code (PHP classes)
│   ├── Controllers/
│   ├── Core/            # Core framework classes (Router, Controller, View, etc.)
│   ├── Middleware/
│   ├── Models/
│   └── Utils/
├── vendor/              # Composer dependencies
├── .env                 # Your local environment configuration (ignored by Git)
├── composer.json        # PHP dependencies
└── docker-compose.yml   # Docker service definitions
```

### Request Lifecycle

1. All HTTP requests are directed by Apache to `public/index.php`.
2. `index.php` initializes the application (autoloader, .env, database).
3. The Router matches the request URI to a defined route.
4. If the route has Middleware (e.g., auth), it is executed. The middleware can halt the request (e.g., redirect to login).
5. If middleware passes, the Router instantiates the appropriate Controller and calls the specified method, injecting the database dependency.
6. The Controller processes the request, interacts with the Model to fetch or save data, and prepares the response.
7. The Controller creates a View instance, passes data to it, and tells it to render.
8. The View renders the final HTML, which is sent back to the user.

## Common Docker Commands

Run these commands from your project's root directory.

**Start the containers:**
```bash
docker compose up -d
```

**Stop the containers:**
```bash
docker compose down
```

**Rebuild and start the containers:**
```bash
docker compose up -d --build
```

**View logs for all services:**
```bash
docker compose logs -f
```

**Execute a command inside the PHP container (e.g., run composer):**
```bash
docker compose exec app composer install
```

**Open a shell inside the PostgreSQL container:**
```bash
docker compose exec postgres psql -U template_user -d template_db
```

## License

This project is open-sourced software licensed under the MIT license.