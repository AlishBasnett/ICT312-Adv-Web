# Part B Documentation Outline

## 1. Introduction

This document describes the implementation of the Secure Salon Appointment Booking System for ICT312 Advanced Web Information Systems Assignment 02. The project implements the system proposed in Assessment 1 using HTML5, CSS3, JavaScript, PHP, and MySQL.

## 2. System Overview

The system is a small web-based appointment booking application for a salon. Customers can register, login, view services, book appointments, view their own appointment records, and cancel eligible appointments. Staff users can view all appointments and update statuses. Admin users can also manage salon services.

## 3. Group Members and Roles

| Member | Role Summary |
| --- | --- |
| Alish Basnet | Main technical development, database setup, PHP functionality, login/register system, booking system, admin dashboard, GitHub management, testing, and final integration. |
| Bipin | Documentation support, user manual preparation, testing feedback, screenshots, and presentation support. |
| Sakar | Installation manual support, testing checklist, report formatting, system review, and presentation support. |

The system was developed collaboratively with task division across coding, documentation, testing, review, and presentation preparation.

## 4. Technologies Used

- HTML5 for page structure
- CSS3 for styling and responsive layout
- JavaScript for simple confirmation and form support
- PHP for server-side processing
- MySQL for database storage
- PDO prepared statements for database queries
- Git and GitHub for version control
- XAMPP/WAMP for local development

## 5. Database Design

### users table

Stores customer, staff, and admin login details.

Main fields:

- `user_id`
- `full_name`
- `email`
- `phone`
- `password_hash`
- `role`
- `created_at`

### services table

Stores salon services shown to customers.

Main fields:

- `service_id`
- `service_name`
- `category`
- `duration_minutes`
- `price`
- `status`

### appointments table

Stores appointment booking records.

Main fields:

- `appointment_id`
- `user_id`
- `service_id`
- `appointment_date`
- `appointment_time`
- `status`
- `notes`
- `created_at`

Relationships:

- One user can have many appointments.
- One service can be used in many appointments.
- The appointments table links users and services using foreign keys.

## 6. Installation Manual

1. Install XAMPP or WAMP.
2. Start Apache and MySQL.
3. Copy the project folder into `htdocs` for XAMPP or `www` for WAMP.
4. Open phpMyAdmin.
5. Import `database.sql`.
6. Check `config/database.php` and update credentials if required.
7. Open `http://localhost/salon-booking-system/`.
8. Test customer registration and admin login.
9. Test staff login and appointment status update.

## 7. User Manual for Customers

1. Open the home page.
2. Register a customer account.
3. Login using the registered details.
4. View active salon services.
5. Book an appointment by selecting a service, date, and time.
6. Open My Appointments to view personal bookings.
7. Cancel an appointment if it is Pending or Confirmed.
8. Logout after use.

## 8. User Manual for Admin

1. Open the login page.
2. Login using the default admin account.
3. View all appointments in the Admin Dashboard.
4. Update appointment status when required.
5. Open Manage Services to add or edit services.
6. Activate or deactivate services depending on salon availability.
7. Logout after completing admin tasks.

## 8A. User Manual for Staff

1. Open the login page.
2. Login using the staff account.
3. View all appointments in the appointment dashboard.
4. Update appointment statuses as required.
5. Logout after completing staff tasks.

## 9. Testing Table

| Test Case | Test Data or Action | Expected Result | Status |
| --- | --- | --- | --- |
| Register new customer | Enter valid customer details | Customer account is created | To be completed |
| Prevent duplicate email | Register using an existing email | Error message is shown | To be completed |
| Login as customer | Use valid customer email/password | Customer dashboard page opens | To be completed |
| Login as admin | Use admin login details | Admin dashboard opens | To be completed |
| Login as staff | Use staff login details | Appointment dashboard opens | To be completed |
| View services | Open services page | Active services are listed | To be completed |
| Book appointment | Select service, future date, and time | Appointment is saved as Pending | To be completed |
| Prevent past date booking | Select a past date | Booking is rejected | To be completed |
| View own appointments | Open My Appointments | Only current customer appointments appear | To be completed |
| Cancel appointment | Cancel Pending or Confirmed appointment | Status becomes Cancelled | To be completed |
| Admin view all appointments | Open admin dashboard | All appointments are visible | To be completed |
| Admin update appointment status | Change status and save | Appointment status updates | To be completed |
| Staff update appointment status | Change status and save | Appointment status updates | To be completed |
| Admin add service | Enter new service details | Service is created | To be completed |
| Admin edit service | Edit service details | Service is updated | To be completed |
| Admin deactivate service | Change status to inactive | Service hidden from public services page | To be completed |
| Logout | Click logout | Session ends and login page opens | To be completed |
| Unauthorised customer cannot access dashboard pages | Customer opens dashboard URL | Access is blocked or redirected | To be completed |
| Staff cannot access service management | Staff opens service management URL | Access is blocked or redirected | To be completed |

## 10. Risk Management Summary

| Risk | Response |
| --- | --- |
| Project may not run on marker computer | Use plain PHP/MySQL and include setup instructions |
| Database import errors | Provide one complete `database.sql` file |
| Login or access control problems | Use sessions and customer/staff/admin role checks in `includes/auth.php` |
| SQL injection risk | Use PDO prepared statements |
| Poor usability | Keep forms, navigation, and tables simple |
| Time pressure near submission | Focus on core required features only |

## 11. Service Management Summary

- The system should be hosted locally for marking using XAMPP or WAMP.
- MySQL should be backed up by exporting the database from phpMyAdmin.
- Staff users should review appointment statuses regularly.
- Admin users should review services and appointments regularly.
- Inactive services can be disabled without deleting records.
- Basic incidents such as failed login, incorrect booking status, or missing service data can be checked through phpMyAdmin and the admin dashboard.

## 12. Change Management Summary

| Step | Description |
| --- | --- |
| Request | Identify the required change or issue |
| Assessment | Check impact on database, pages, and schedule |
| Implementation | Make the update in code |
| Testing | Test affected customer, staff, and admin functions |
| Version Control | Commit the working change to Git |
| Review | Confirm the system still works locally |

## 13. Version Control Evidence

Git and GitHub are used to record the progress of the project. Evidence can include screenshots of the GitHub repository, commit history, and final project files.

Suggested commit messages:

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

## 14. Part A Weekly Progress Plan

| Week | Progress |
| --- | --- |
| Week 5 | Project scope confirmed, proposal reviewed, roles discussed. |
| Week 6 | Database design, page structure, and system workflow planned. |
| Week 7 | Registration, login, and basic page layout planned/developed. |
| Week 8 | Services page, booking workflow, and admin requirements finalised. |
| Week 9 | GitHub repository created, database schema implemented, main PHP structure started. |
| Week 10 | Customer booking features, my appointments page, and admin dashboard developed. |
| Week 11 | Service management, validation, security checks, testing, and documentation completed. |
| Week 12 | Final bug fixes, presentation preparation, final zip file, and submission check. |

## 15. Conclusion

The Secure Salon Appointment Booking System meets the core Assignment 02 requirements by implementing a working PHP and MySQL web application. It supports customer registration, login, booking, appointment viewing, cancellation, staff appointment management, admin service management, and role-based access control. The project is intentionally simple so it can run locally and be assessed easily using XAMPP or WAMP.
