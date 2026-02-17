# Document Approval System

## Overview
A simple document approval system built with Laravel 12 and PostgreSQL.
Users can upload documents, and Administrators can approve or reject them.

## Requirements
- PHP 8.2+
- PostgreSQL
- Composer
- Node.js (optional, for asset compilation if needed)

## Setup Instructions

1.  **Clone/Copy Project**
    ```bash
    git clone <repository-url>
    cd doc-managment
    ```

2.  **Install Dependencies**
    ```bash
    composer install
    ```

3.  **Environment Configuration**
    - Copy `.env.example` to `.env`.
    - Generate application key:
        ```bash
        php artisan key:generate
        ```
    - Update DB credentials:
        ```ini
        DB_CONNECTION=pgsql
        DB_HOST=127.0.0.1
        DB_PORT=5432
        DB_DATABASE=doc_management
        DB_USERNAME=postgres
        DB_PASSWORD=your_password
        ```

4.  **Database Migration & Seeding**
    ```bash
    php artisan migrate --seed
    ```
    > [!TIP]
    > If the migration command does not work, please use the DB dump file located at `database/doc_management.sql`.

    This will create the tables and seed:
    - Admin: `admin@example.com` / `password`
    - User: `user@example.com` / `password`

5.  **Storage Link**
    ```bash
    php artisan storage:link
    ```

6.  **Run Application**
    ```bash
    php artisan serve
    ```

## Features & Usage

### User Panel
- Login with `user@example.com`.
- Upload documents (PDF, DOCX).
- View status of uploaded documents.
- Delete own documents.

### Admin Panel
- Login with `admin@example.com`.
- Access Admin Dashboard via link in main dashboard.
- View pending documents.
- Approve or Reject documents (AJAX supported).

### Automation
- **Cleanup Command**: `php artisan documents:cleanup`
    - Deletes rejected documents older than 30 days.
    - Scheduled to run daily in `routes/console.php`.

## Project Structure
- **Routes**: `routes/web.php` (Auth, User, Admin groups).
- **Controllers**:
    - `DocumentController`: User actions.
    - `AdminDocumentController`: Admin actions.
    - `AuthController`: Custom auth logic.
- **Models**: `User`, `Document`, `DocumentLog`.
- **Views**: Blade templates using Bootstrap 5.
- **Middleware**: `IsAdmin` (registered as `is_admin`).

## Database Schema
- `users`: Standard Laravel users + `is_admin` actions.
- `documents`: Stores file paths and status.
- `document_logs`: Tracks approval/rejection history.

## Important Notes

### Laravel 11 Scheduler Changes
> **Note**: In Laravel 11, there is **no `Kernel.php` file**. Task scheduling has been simplified and moved to `routes/console.php`.
> 
> The cleanup command is scheduled in [`routes/console.php`](routes/console.php):
> ```php
> Schedule::command('documents:cleanup')->daily();
> ```
> 
> This is a major improvement over Laravel 10 and earlier versions, making scheduling more intuitive and reducing boilerplate code.
