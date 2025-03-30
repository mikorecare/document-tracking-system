# Laravel Project Setup Guide

Follow these steps to set up your Laravel project from scratch.

## Prerequisites

Ensure you have the following installed:

- **PHP** (Recommended: PHP 8.0 or later)
- **Composer** (Dependency Manager for PHP)
- **MySQL** (Database)
- **Node.js & npm** (For frontend assets, if applicable)
- **Git** (Version Control System)

## 🚀 Clone the Project Repository

```bash
git clone https://github.com/your-repo/project-name.git
cd project-name

Install Dependencies

composer install
⚙️ Create and Configure the Environment File
Copy the example environment file and update it:

cp .env.example .env
Edit .env and configure the following:
APP_NAME="Your Laravel App"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306 # Change if needed (e.g., 3307)
DB_DATABASE=your_database_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
Generate the application key:


php artisan key:generate
🗄️ Configure Database
Create a new database in MySQL:

CREATE DATABASE your_database_name;
Ensure the database credentials in .env match your MySQL setup.

📊 Run Migrations and Seed Data (if applicable)

php artisan migrate --seed

🌐 Serve the Application
Start the Laravel development server:

php artisan serve --port=8080  # Change port if needed
Visit http://127.0.0.1:8080 in your browser.

🎨 Configure Frontend (Optional)
If the project has frontend dependencies:

npm install && npm run dev
📂 Set Up Storage Link (Optional, for file uploads)

php artisan storage:link
⚡ Setting Up Queue Workers (If Required)
If the project uses queues:

php artisan queue:work
🧪 Running Tests (If applicable)

php artisan test
🛠️ Troubleshooting
Port Conflicts: If php artisan serve fails, try changing the port: 

php artisan serve --port=8081
Missing Extensions: Ensure required PHP extensions are installed using 

php -m
Permission Issues: Adjust folder permissions: 

chmod -R 775 storage bootstrap/cache
Happy coding! 🚀


Feel free to modify any section as necessary to better fit your project specific
