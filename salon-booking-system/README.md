# Secure Salon Appointment Booking System

ICT312 Advanced Web Information Systems - Assignment 02

## Group Members

- Alish Basnet
- Bipin
- Sakar

## Project Overview

The Secure Salon Appointment Booking System is a simple PHP and MySQL web application for a small salon. Customers can register, login, view services, book appointments, and manage their own bookings. Staff users can manage appointment statuses, while admin users can also manage salon services.

The system is designed to run locally using XAMPP or WAMP through `localhost`.

## Business Problem

Small salons often manage appointments through phone calls, messages, walk-ins, notebooks, or social media. This can cause double bookings, missed appointments, poor record keeping, and privacy risks when customer information is not protected.

This system provides a central booking database and protected login access for customers, staff, and admin users.

## Technologies Used

- HTML5
- CSS3
- JavaScript
- PHP
- MySQL
- Git and GitHub

No external frameworks, APIs, or advanced services are required.

## Main Features

- Customer registration with duplicate email checking
- Customer, staff, and admin login from one login page
- Secure password storage using `password_hash()`
- Login checking using `password_verify()`
- PHP session management
- Active services page
- Customer appointment booking
- Past date booking prevention
- Customer-only appointment history
- Customer appointment cancellation for Pending or Confirmed bookings
- Admin dashboard for all appointments
- Staff dashboard access for appointment status updates
- Admin appointment status updates
- Admin service add/edit/activate/deactivate feature
- Prepared statements with PDO
- Output escaping with `htmlspecialchars()`

## Folder Structure

```text
salon-booking-system/
  config/
    database.php
  includes/
    header.php
    footer.php
    auth.php
  assets/
    css/
      style.css
    img/
      salon-hero.svg
    js/
      script.js
  admin/
    dashboard.php
    manage_services.php
  index.php
  register.php
  login.php
  services.php
  book.php
  my_appointments.php
  logout.php
  database.sql
  README.md
  DOCUMENTATION_OUTLINE.md
  .gitignore
```

## Database Setup Instructions

Database name: `salondb`

Tables:

- `users`
- `services`
- `appointments`

The database file is:

```text
database.sql
```

It creates the database, creates all required tables, inserts one default admin account, one default staff account, and more than twenty sample salon services.

## XAMPP/WAMP Setup Instructions

1. Install and open XAMPP or WAMP.
2. Start Apache.
3. Start MySQL.
4. Copy the `salon-booking-system` folder into the local web server folder.

For XAMPP, the usual path is:

```text
xampp/htdocs/salon-booking-system/
```

For WAMP, the usual path is:

```text
wamp64/www/salon-booking-system/
```

## Import database.sql into phpMyAdmin

1. Open phpMyAdmin in the browser:

```text
http://localhost/phpmyadmin
```

2. Click the Import tab.
3. Choose the `database.sql` file from the `salon-booking-system` folder.
4. Click Go.
5. Confirm that the `salondb` database appears in phpMyAdmin.

## Update config/database.php

The default database settings are for XAMPP/WAMP:

```php
$host = 'localhost';
$dbname = 'salondb';
$username = 'root';
$password = '';
```

If your MySQL username or password is different, update:

```text
config/database.php
```

## Run the Project in Browser

After copying the project into `htdocs` or `www`, open:

```text
http://localhost/salon-booking-system/
```

## Default Admin Login Details

Email:

```text
admin@salon.local
```

Password:

```text
Admin@123
```

The admin password is stored in the database as a bcrypt hash and checked using `password_verify()`.

## Default Staff Login Details

Email:

```text
staff@salon.local
```

Password:

```text
Staff@123
```

Staff users can access the appointment dashboard and update appointment statuses, but service management is restricted to admin users.

## Customer User Manual

1. Open `http://localhost/salon-booking-system/`.
2. Click Register.
3. Enter full name, email, phone, password, and confirm password.
4. Click Register.
5. Login using the registered email and password.
6. Click Services to view available salon services.
7. Click Book or open Book Appointment.
8. Select a service, future date, time, and optional notes.
9. Submit the booking. The status will be Pending.
10. Open My Appointments to view bookings.
11. Cancel a booking if the status is Pending or Confirmed.
12. Click Logout when finished.

## Admin User Manual

1. Open `http://localhost/salon-booking-system/login.php`.
2. Login using the default admin account.
3. Open Admin Dashboard.
4. View all customer appointments.
5. Change appointment status to Pending, Confirmed, Completed, or Cancelled.
6. Open Manage Services.
7. Add new services.
8. Edit service name, category, duration, price, and status.
9. Set a service to inactive if it should not appear on the public services page.
10. Click Logout when finished.

## Staff User Manual

1. Open `http://localhost/salon-booking-system/login.php`.
2. Login using the default staff account.
3. Open the appointment dashboard.
4. View customer appointment details.
5. Update appointment status to Pending, Confirmed, Completed, or Cancelled.
6. Logout when finished.

## Testing Table

| Test Case | Expected Result |
| --- | --- |
| Register new customer | Customer account is created and user can login |
| Prevent duplicate email | Registration shows duplicate email error |
| Login as customer | Customer is redirected to My Appointments |
| Login as admin | Admin is redirected to Admin Dashboard |
| Login as staff | Staff is redirected to Appointment Dashboard |
| View services | Active services are displayed |
| Book appointment | Appointment is saved with Pending status |
| Prevent past date booking | Past date booking is rejected |
| View own appointments | Customer sees only their own appointments |
| Cancel appointment | Pending or Confirmed appointment changes to Cancelled |
| Admin view all appointments | Admin can see all customer appointments |
| Admin update appointment status | Selected appointment status is updated |
| Staff update appointment status | Selected appointment status is updated |
| Admin add service | New service is saved in the services table |
| Admin edit service | Existing service details are updated |
| Admin deactivate service | Service becomes inactive and is hidden from services page |
| Logout | Session is destroyed and user returns to login page |
| Unauthorised customer cannot access dashboard pages | Customer is redirected and dashboard page is protected |
| Staff cannot access service management | Staff is redirected away from admin-only service management |

## Troubleshooting

### Database connection failed

Check that MySQL is running and that `config/database.php` has the correct database username and password.

### Page not found

Make sure the folder is named exactly:

```text
salon-booking-system
```

and placed inside `htdocs` or `www`.

### Login does not work

Make sure `database.sql` was imported successfully and that the `users` table contains the default admin and staff accounts.

### Services do not appear

Check that the `services` table contains active services.

### Admin page access denied

Only users with role `admin` or `staff` can access the appointment dashboard. Only users with role `admin` can access service management.

## GitHub and Version Control Explanation

Git is used to track project changes during development. GitHub can be used to store the repository online and show version control evidence for the assignment.

Suggested development commits:

- Initial project setup for salon booking system
- Added MySQL database schema
- Added registration and login functionality
- Added services page
- Added appointment booking feature
- Added customer appointments page
- Added admin dashboard
- Added staff role dashboard access
- Added service management feature
- Improved validation and session security
- Updated README installation guide
- Final tested version for submission

Do not commit local passwords, private credentials, cache files, IDE folders, or zip submission files.
