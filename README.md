# COVID Vaccine Registration System

This is a COVID Vaccine Registration System built using Laravel 11 for the backend and Vue.js with Vuetify for the frontend. The system allows users to register for vaccines, check their registration status, and manage vaccination centers.

## Requirements

Before you begin, ensure you have the following installed on your machine:

- **PHP** >= 8.1
- **Composer**
- **Node.js** with **npm**
- **Docker** (Optional, for containerized development)

## Setup Instructions

Follow these steps to set up the project on your local machine:

### 1. Clone the repository

Use the following command to clone the project repository:

```bash
git clone https://github.com/firozwebdev/covid_vaccine.git

```
### 2. Change into the project directory:

```bash
cd covid-vaccine-registration

```

### 3. Use Composer to install the required PHP dependencies:

```bash
composer install

```
Use npm to install the required JavaScript dependencies:

```bash
npm install

```
Copy the .env.example file to create your own environment configuration:

```bash
cp .env.example .env

```

Run this command to generate the Laravel application key:

```bash
php artisan key:generate

```

To set up the database schema, run the migrations:

```bash
php artisan migrate

```
 Populate  database with initial data (Seeder), you can run:

```bash
php artisan db:seed

```

For local development:

```bash
npm run dev

```