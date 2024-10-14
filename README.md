# COVID Vaccine Registration System

This is a COVID Vaccine Registration System built using Laravel 11 for the backend and Vue.js for the frontend. The system allows users to register for vaccines, check their registration status, and manage vaccination centers.
## Project Description

### User Registration: 
Users can register themselves in order to get vaccined, as soon they register, they will be notified with email as well as sms. At the time of registration, user can give various information like name, email, nid, mobile no. etc. But here nid is unique and must be exact 10 digit. A user can only register once with a nid. 
### Search:  
Users can search themeselves if they put nid to know their status (Not scheduled, Scheduled, Vaccinated etc.). If any user does not register, he/she can show herself as not registered.

### Schedule:  
In scheduled secition, Authority will give any user scheduled date or scheduled date will be placed as first come first serve. If any user is scheduled, he or she will be got notified with email as well as sms. Any scheduled user will be got notied at 9pm. night just before the schedule date.

### Status: 
If the scheduled date is passed, that user's status will be vaccinated.

### Available Commands:
####  Schedule users as first come first serve within Sunday to Thursday.
```bash
app(Schedule::class)->command('give:schedule')->everySixHours();

```

####  Scheduled User's Reminder at night (9pm) just before the scheduled date.
```bash
app(Schedule::class)->command('schedule:vaccination-reminder')->dailyAt('21:00');

```

####  Every User's status will be 'Vaccinated' if the schedule date is passed
```bash
app(Schedule::class)->command('status:update-vaccination-status')->daily()->at('00:00');

```


## Can be Improved (due to time shortness):
### 1. UI could be better with Vuetify or any other vueJs ui
### 2. Enhancements:

Batch Scheduling: For large user bases, consider using batch processing or queues to handle the scheduling and notifications to avoid overwhelming the system.

Search Optimization: Optimize the search functionality for user registration and scheduling to improve speed, especially with large datasets.

Real-Time Scheduling Updates: Provide real-time feedback to users regarding their scheduling status through the user interface, showing them the estimated time until they will be scheduled.

Analytics Dashboard: Create a detailed admin dashboard showing real-time data about vaccination progress, center capacity, user registration stats, and daily scheduling.

Role-based Access Control: Implement role-based permissions for different users, such as admins, healthcare workers, and users, with distinct features and views for each.

Automated Tests: Add automated unit and integration tests for critical parts of the scheduling, notification, and user registration process.

Fault Tolerance: Implement error handling mechanisms to ensure that any failure during scheduling or notifications does not crash the entire system

Post-Vaccination Surveys: Allow users to submit feedback or a survey after their vaccination to monitor user experience and improve service quality.

## Requirements

Before you begin, ensure you have the following installed on your machine:

- **PHP** >= 8.1
- **Composer**
- **Node.js** with **npm**
- **Docker** (Optional, for containerized development)

## Setup Instructions

Follow these steps to set up the project on your local machine:

#### 1. Clone the repository

Use the following command to clone the project repository:

```bash
git clone https://github.com/firozwebdev/covid_vaccine.git

```
#### 2. Change into the project directory:

```bash
cd covid_vaccine

```

#### 3. Use Composer to install the required PHP dependencies:

```bash
composer install

```
#### 4. Use npm to install the required JavaScript dependencies:

```bash
npm install

```
#### 5. Copy the .env.example file to create your own environment configuration:

```bash
cp .env.example .env

```

#### 6. Run this command to generate the Laravel application key:

```bash
php artisan key:generate

```

#### 7. To set up the database schema, run the migrations:

```bash
php artisan migrate

```
#### 8. Populate  database with initial data (Seeder), you can run:

```bash
php artisan db:seed

```

#### 9. Make sure you run the queue worker to process the notifications:

```bash
php artisan queue:work --daemon

```

#### 9. For local development:

```bash
npm run dev

```

#### 10. Check for available schedules:

```bash
php artisan schedule:list

```
#### 10. Run available schedule :

```bash
php artisan schedule:run

```